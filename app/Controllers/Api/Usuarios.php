<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AreaModel;
use App\Models\DepartmentModel;
use App\Models\OcupationModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Usuarios extends BaseController
{
    protected $userModel;
    protected $areaModel;
    protected $departmentModel;
    protected $ocupationModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel       = new UserModel();
        $this->areaModel       = new AreaModel();
        $this->departmentModel = new DepartmentModel();
        $this->ocupationModel  = new OcupationModel();
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

        foreach (['no_empleado', 'nombre', 'apellidos', 'correo', 'password', 'puesto'] as $campo) {
            if (empty($body[$campo])) {
                return $this->json([
                    'exito'        => false,
                    'error_codigo' => 'CAMPO_REQUERIDO',
                    'mensaje'      => "El campo '{$campo}' es requerido",
                ], 400);
            }
        }

        $duplicado = $this->userModel
            ->where('employee_number', $body['no_empleado'])
            ->withDeleted()
            ->first();

        if ($duplicado) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_DUPLICADO',
                'mensaje'      => 'Ya existe un usuario con ese no_empleado',
            ], 409);
        }

        $areaId       = $this->resolverOCrear($this->areaModel,       'getAreaByName',       'createArea',       $body['area'] ?? null);
        $departmentId = $this->resolverOCrear($this->departmentModel,  'getDepartmentByName', 'createDepartment', $body['departamento'] ?? null);
        $ocupationId  = $this->resolverOCrear($this->ocupationModel,   'getOcupationByName',  'createOcupation',  $body['puesto']);

        $parentId = null;
        if (!empty($body['jefe_directo'])) {
            $jefe = $this->userModel->where('employee_number', $body['jefe_directo'])->first();
            if (!$jefe) {
                return $this->json([
                    'exito'        => false,
                    'error_codigo' => 'JEFE_NO_ENCONTRADO',
                    'mensaje'      => 'No se encontró el jefe_directo con no_empleado ' . $body['jefe_directo'],
                ], 422);
            }
            $parentId = $jefe->id;
        }

        $activo = (!isset($body['estado']) || $body['estado'] === 'activo') ? 1 : 0;

        $id = $this->userModel->insert([
            'employee_number'   => $body['no_empleado'],
            'name'              => $body['nombre'],
            'lastname'          => $body['apellidos'],
            'email'             => $body['correo'],
            'password'          => password_hash($body['password'], PASSWORD_DEFAULT),
            'area'              => $areaId,
            'department'        => $departmentId,
            'ocupation'         => $ocupationId,
            'parent'            => $parentId,
            'active'            => $activo,
            'rol'               => 'user',
            'photo'             => 'assets/images/profile/default_profile.png',
            'show_in_directory' => 1,
            'hide_emails'       => 0,
            'ghost'             => 0,
            'has_ghost'         => 0,
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

    // POST /api/v1/usuarios/{no_empleado}/desactivar
    public function desactivar($no_empleado)
    {
        $user = $this->userModel->where('employee_number', $no_empleado)->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese no_empleado',
            ], 404);
        }

        $this->userModel->inactiveUser($user->id);

        return $this->json(['exito' => true, 'mensaje' => 'Usuario desactivado']);
    }

    // POST /api/v1/usuarios/{no_empleado}/password
    public function actualizarPassword($no_empleado)
    {
        $body = $this->request->getJSON(true) ?? [];

        if (empty($body['password'])) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'CAMPO_REQUERIDO',
                'mensaje'      => "El campo 'password' es requerido",
            ], 400);
        }

        $user = $this->userModel->where('employee_number', $no_empleado)->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese no_empleado',
            ], 404);
        }

        $this->userModel->setNewPassword($user->id, password_hash($body['password'], PASSWORD_DEFAULT));

        return $this->json(['exito' => true, 'mensaje' => 'Contrasena actualizada']);
    }

    // PUT /api/v1/usuarios/{no_empleado}
    public function actualizar($no_empleado)
    {
        $body = $this->request->getJSON(true) ?? [];

        $user = $this->userModel
            ->where('employee_number', $no_empleado)
            ->withDeleted()
            ->first();

        if (!$user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No se encontró un usuario con ese no_empleado',
            ], 404);
        }

        $data = [];

        if (array_key_exists('nombre', $body))    $data['name']     = $body['nombre'];
        if (array_key_exists('apellidos', $body))  $data['lastname'] = $body['apellidos'];
        if (array_key_exists('correo', $body))     $data['email']    = $body['correo'];

        if (array_key_exists('area', $body)) {
            $data['area'] = $this->resolverOCrear($this->areaModel, 'getAreaByName', 'createArea', $body['area']);
        }

        if (array_key_exists('departamento', $body)) {
            $data['department'] = $this->resolverOCrear($this->departmentModel, 'getDepartmentByName', 'createDepartment', $body['departamento']);
        }

        if (array_key_exists('puesto', $body)) {
            $data['ocupation'] = $this->resolverOCrear($this->ocupationModel, 'getOcupationByName', 'createOcupation', $body['puesto']);
        }

        if (array_key_exists('jefe_directo', $body)) {
            if ($body['jefe_directo'] === null) {
                $data['parent'] = null;
            } else {
                $jefe = $this->userModel->where('employee_number', $body['jefe_directo'])->first();
                if (!$jefe) {
                    return $this->json([
                        'exito'        => false,
                        'error_codigo' => 'JEFE_NO_ENCONTRADO',
                        'mensaje'      => 'No se encontró el jefe_directo con no_empleado ' . $body['jefe_directo'],
                    ], 422);
                }
                $data['parent'] = $jefe->id;
            }
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

    private function resolverOCrear($model, string $findMethod, string $createMethod, ?string $nombre): ?int
    {
        if ($nombre === null || $nombre === '') {
            return null;
        }

        $registro = $model->$findMethod($nombre);

        return $registro ? $registro->id : $model->$createMethod($nombre);
    }
}
