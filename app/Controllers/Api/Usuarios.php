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

        $id = $this->userModel->createUser([
            'nexus_id' => $body['nexus_id'],
            'name'     => $body['nombre'],
            'lastname' => $body['apellidos'],
            'email'    => $body['correo'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'rol'      => 'user',
            'active'   => $activo,
        ]);

        if (!$id) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'ERROR_INTERNO',
                'mensaje'      => 'No se pudo crear el usuario',
            ], 500);
        }

        return $this->json([
            'exito'      => true,
            'id_usuario' => 'INT-' . $id,
            'mensaje'    => 'Usuario creado',
        ]);
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

        if (empty($data)) {
            return $this->json(['exito' => true, 'mensaje' => 'Sin cambios que aplicar']);
        }

        $this->userModel->update($user->id, $data);

        return $this->json([
            'exito'      => true,
            'id_usuario' => 'INT-' . $user->id,
            'mensaje'    => 'Usuario actualizado',
        ]);
    }
}
