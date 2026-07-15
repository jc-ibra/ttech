<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * API v1 para el sistema externo Nexus.
 *
 * Nexus administra las CUENTAS DE LOGIN (rol `user`) de la intranet enviando
 * únicamente la información mínima: nexus_id, nombre, apellidos, correo y
 * contraseña. Los datos de organigrama (puesto, área, jefe, etc.) ya NO se
 * manejan por aquí: eso vive en la sección de Empleados.
 *
 * Todos los endpoints por recurso se identifican con el `nexus_id`.
 */
class Usuarios extends BaseController
{
    protected $userModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
    }

    private function json(array $data, int $status = 200): ResponseInterface
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }

    // GET /api/v1/ping
    public function ping()
    {
        return $this->json(['exito' => true, 'mensaje' => 'pong']);
    }

    // POST /api/v1/usuarios
    public function crear()
    {
        $body = $this->request->getJSON(true) ?? [];

        foreach (['nexus_id', 'nombre', 'apellidos', 'correo', 'password'] as $campo) {
            if (empty($body[$campo])) {
                return $this->json([
                    'exito'        => false,
                    'error_codigo' => 'CAMPO_REQUERIDO',
                    'mensaje'      => "El campo '{$campo}' es requerido",
                ], 400);
            }
        }

        // Evitar duplicados por nexus_id.
        $duplicado = $this->userModel
            ->where('nexus_id', $body['nexus_id'])
            ->withDeleted()
            ->first();

        if ($duplicado) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_DUPLICADO',
                'mensaje'      => 'Ya existe un usuario con ese nexus_id',
            ], 409);
        }

        // Evitar duplicados por correo (el login es por correo).
        if ($this->userModel->where('email', $body['correo'])->withDeleted()->first()) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'CORREO_DUPLICADO',
                'mensaje'      => 'Ya existe un usuario con ese correo',
            ], 409);
        }

        $activo = (!isset($body['estado']) || $body['estado'] === 'activo') ? 1 : 0;

        // El número de empleado es opcional/tolerante: si viene vacío o no
        // viene, se guarda NULL sin fallar. No es llave de recurso.
        $numeroEmpleado = isset($body['numero_empleado']) && $body['numero_empleado'] !== ''
            ? trim((string) $body['numero_empleado'])
            : null;

        if ($numeroEmpleado !== null && mb_strlen($numeroEmpleado) > 20) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'CAMPO_INVALIDO',
                'mensaje'      => "El campo 'numero_empleado' no puede exceder 20 caracteres",
            ], 400);
        }

        $id = $this->userModel->createUser([
            'nexus_id'        => $body['nexus_id'],
            'employee_number' => $numeroEmpleado,
            'name'            => $body['nombre'],
            'lastname'        => $body['apellidos'],
            'email'           => $body['correo'],
            'password'        => password_hash($body['password'], PASSWORD_DEFAULT),
            'rol'             => 'user',
            'active'          => $activo,
        ]);

        if (!$id) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'ERROR_INTERNO',
                'mensaje'      => 'No se pudo crear el usuario',
            ], 500);
        }

        $respuesta = [
            'exito'      => true,
            'id_usuario' => 'INT-' . $id,
            'mensaje'    => 'Usuario creado',
        ];

        // Foto opcional: si Nexus la envía, se decodifica y se asocia al usuario.
        // Si algo falla con la foto, el usuario ya quedó creado: solo advertimos.
        if (!empty($body['foto_base64'])) {
            $foto = $this->guardarFotoBase64($body['foto_base64'], $body['foto_mime'] ?? '');

            if ($foto['ok']) {
                $this->userModel->setNewPhoto($id, $foto['ruta']);
            } else {
                $respuesta['advertencia'] = 'Usuario creado, pero la foto no se pudo guardar: ' . $foto['mensaje'];
            }
        }

        return $this->json($respuesta);
    }

    /**
     * Decodifica una imagen en base64 (sin prefijo data:) y la guarda en
     * public/uploads/images/profiles, igual que las fotos de perfil normales.
     *
     * @return array{ok: bool, ruta?: string, mensaje?: string}
     */
    private function guardarFotoBase64(string $base64, string $mime): array
    {
        $extensiones = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensiones[$mime])) {
            return ['ok' => false, 'mensaje' => 'tipo MIME no soportado (use image/jpeg, image/png o image/webp)'];
        }

        // Tolerar un prefijo data: por si llegara, aunque el contrato pide base64 puro.
        if (($coma = strpos($base64, ',')) !== false && str_starts_with($base64, 'data:')) {
            $base64 = substr($base64, $coma + 1);
        }

        $binario = base64_decode($base64, true);
        if ($binario === false) {
            return ['ok' => false, 'mensaje' => 'foto_base64 no es un base64 válido'];
        }

        // Hasta ~2 MB de imagen original.
        if (strlen($binario) > 2 * 1024 * 1024) {
            return ['ok' => false, 'mensaje' => 'la imagen supera el tamaño máximo de 2 MB'];
        }

        // Verificar que el contenido realmente sea una imagen.
        if (@getimagesizefromstring($binario) === false) {
            return ['ok' => false, 'mensaje' => 'el contenido no es una imagen válida'];
        }

        $uploadPath = ROOTPATH . 'public/uploads/images/profiles';
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
            return ['ok' => false, 'mensaje' => 'no se pudo preparar el directorio de imágenes'];
        }

        $nombre = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];

        if (file_put_contents($uploadPath . '/' . $nombre, $binario) === false) {
            return ['ok' => false, 'mensaje' => 'no se pudo escribir la imagen en disco'];
        }

        return ['ok' => true, 'ruta' => 'uploads/images/profiles/' . $nombre];
    }

    // POST /api/v1/usuarios/{nexus_id}/desactivar
    public function desactivar($nexusId)
    {
        $user = $this->userModel->where('nexus_id', $nexusId)->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese nexus_id',
            ], 404);
        }

        $this->userModel->inactiveUser($user->id);

        return $this->json(['exito' => true, 'mensaje' => 'Usuario desactivado']);
    }

    // POST /api/v1/usuarios/{nexus_id}/password
    public function actualizarPassword($nexusId)
    {
        $body = $this->request->getJSON(true) ?? [];

        if (empty($body['password'])) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'CAMPO_REQUERIDO',
                'mensaje'      => "El campo 'password' es requerido",
            ], 400);
        }

        $user = $this->userModel->where('nexus_id', $nexusId)->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese nexus_id',
            ], 404);
        }

        $this->userModel->setNewPassword($user->id, password_hash($body['password'], PASSWORD_DEFAULT));

        return $this->json(['exito' => true, 'mensaje' => 'Contrasena actualizada']);
    }

    // PUT /api/v1/usuarios/{nexus_id}
    public function actualizar($nexusId)
    {
        $body = $this->request->getJSON(true) ?? [];

        $user = $this->userModel
            ->where('nexus_id', $nexusId)
            ->withDeleted()
            ->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese nexus_id',
            ], 404);
        }

        $data = [];

        if (array_key_exists('nombre', $body))    $data['name']     = $body['nombre'];
        if (array_key_exists('apellidos', $body)) $data['lastname'] = $body['apellidos'];

        // Actualización parcial del número de empleado: sólo se toca si llega
        // con valor. Si el campo no viene (o viene vacío), se deja intacto y
        // NO se pone en NULL. No es llave de búsqueda: seguimos usando nexus_id.
        if (isset($body['numero_empleado']) && $body['numero_empleado'] !== '') {
            $numeroEmpleado = trim((string) $body['numero_empleado']);

            if (mb_strlen($numeroEmpleado) > 20) {
                return $this->json([
                    'exito'        => false,
                    'error_codigo' => 'CAMPO_INVALIDO',
                    'mensaje'      => "El campo 'numero_empleado' no puede exceder 20 caracteres",
                ], 400);
            }

            $data['employee_number'] = $numeroEmpleado;
        }

        if (array_key_exists('correo', $body)) {
            $existente = $this->userModel->where('email', $body['correo'])->withDeleted()->first();
            if ($existente && $existente->id != $user->id) {
                return $this->json([
                    'exito'        => false,
                    'error_codigo' => 'CORREO_DUPLICADO',
                    'mensaje'      => 'Ya existe un usuario con ese correo',
                ], 409);
            }
            $data['email'] = $body['correo'];
        }

        if (array_key_exists('estado', $body)) {
            $data['active'] = $body['estado'] === 'activo' ? 1 : 0;
        }

        if (array_key_exists('password', $body) && !empty($body['password'])) {
            $data['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }

        // Foto opcional: si llega, reemplaza la actual; si no llega, se deja igual.
        $advertenciaFoto = null;
        $fotoAnterior    = null;
        if (!empty($body['foto_base64'])) {
            $foto = $this->guardarFotoBase64($body['foto_base64'], $body['foto_mime'] ?? '');

            if ($foto['ok']) {
                $data['photo'] = $foto['ruta'];
                $fotoAnterior  = $user->photo;
            } else {
                $advertenciaFoto = 'La foto no se pudo actualizar: ' . $foto['mensaje'];
            }
        }

        if (empty($data)) {
            $respuesta = ['exito' => true, 'mensaje' => 'Sin cambios que aplicar'];
            if ($advertenciaFoto) {
                $respuesta['advertencia'] = $advertenciaFoto;
            }
            return $this->json($respuesta);
        }

        $this->userModel->update($user->id, $data);

        // Borrar la foto anterior solo si se reemplazó y no era la genérica.
        if ($fotoAnterior && $fotoAnterior !== 'assets/images/anonimo.jpg'
            && file_exists(ROOTPATH . 'public/' . $fotoAnterior)) {
            @unlink(ROOTPATH . 'public/' . $fotoAnterior);
        }

        $respuesta = [
            'exito'      => true,
            'id_usuario' => 'INT-' . $user->id,
            'mensaje'    => 'Usuario actualizado',
        ];
        if ($advertenciaFoto) {
            $respuesta['advertencia'] = $advertenciaFoto;
        }

        return $this->json($respuesta);
    }
}
