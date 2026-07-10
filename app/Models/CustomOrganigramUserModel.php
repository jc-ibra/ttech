<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomOrganigramUserModel extends Model
{
    protected $table              = 'custom_organigrama_users';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = false;
    protected $allowedFields      = ['organigrama_id', 'user_id', 'parent_id', 'niveles', 'position_order'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Obtener usuarios de un organigrama específico
     */
    public function getUsersByOrganigrama($organigramaId)
    {
        return $this->select('custom_organigrama_users.*, employees.name, employees.lastname, employees.photo, ocupations.name as ocupation_name')
                    ->join('employees', 'employees.id = custom_organigrama_users.user_id')
                    ->join('ocupations', 'ocupations.id = employees.ocupation', 'left')
                    ->where('custom_organigrama_users.organigrama_id', $organigramaId)
                    ->orderBy('custom_organigrama_users.position_order', 'ASC')
                    ->findAll();
    }

    /**
     * Agregar usuario a un organigrama
     */
    public function addUserToOrganigrama($organigramaId, $userId, $parentId = null, $niveles = 0, $positionOrder = 0)
    {
        $data = [
            'organigrama_id' => $organigramaId,
            'user_id'        => $userId,
            'parent_id'      => $parentId,
            'niveles'        => $niveles,
            'position_order' => $positionOrder,
        ];

        return $this->insert($data);
    }

    /**
     * Actualizar configuración de usuario en organigrama
     */
    public function updateUserInOrganigrama($id, $parentId = null, $niveles = 0)
    {
        return $this->update($id, [
            'parent_id' => $parentId,
            'niveles'   => $niveles,
        ]);
    }

    /**
     * Eliminar usuario de un organigrama
     */
    public function removeUserFromOrganigrama($id)
    {
        return $this->delete($id);
    }

    /**
     * Eliminar todos los usuarios de un organigrama
     */
    public function removeAllUsersFromOrganigrama($organigramaId)
    {
        return $this->where('organigrama_id', $organigramaId)->delete();
    }

    /**
     * Verificar si un usuario ya está en el organigrama
     */
    public function isUserInOrganigrama($organigramaId, $userId)
    {
        return $this->where('organigrama_id', $organigramaId)
                    ->where('user_id', $userId)
                    ->first() !== null;
    }

    /**
     * Construir árbol de organigrama
     */
    private function buildOrganizationTree($users)
    {
        // 1. Construir arreglo asociativo primero sin procesar niveles
        $usersById = [];
        
        foreach ($users as $user) {
            $usersById[$user->user_id] = [
                'id'      => $user->user_id,
                'name'    => $user->name . ' ' . $user->lastname,
                'title'   => $user->ocupation_name ?? 'Sin puesto',
                'pid'     => $user->parent_id,
                'img'     => base_url($user->photo),
                'ghost'   => false,
                'niveles' => $user->niveles,
                'original_pid' => $user->parent_id,  // Guardar el parent original
                'children'=> []
            ];
        }
        
        // 1.5. Procesar niveles e insertar UN nodo ghost por usuario con niveles
        $ghostIdCounter = 20000000;
        $ghostsToAdd = [];
        
        foreach ($usersById as $userId => &$user) {
            if ($user['niveles'] > 0) {
                // Crear UN SOLO nodo ghost con la altura correspondiente
                $ghostId = $ghostIdCounter++;
                $ghostsToAdd[$ghostId] = [
                    'id'      => $ghostId,
                    'name'    => '',
                    'title'   => '',
                    'pid'     => $user['original_pid'],
                    'img'     => '',
                    'ghost'   => true,
                    'niveles' => $user['niveles'],  // El CSS determina la altura
                    'children'=> []
                ];
                
                // Actualizar el pid del usuario para que apunte al ghost
                $user['pid'] = $ghostId;
            }
        }
        unset($user);
        
        // Agregar los ghosts al array principal
        $usersById = $usersById + $ghostsToAdd;

        // 2. Ajustar pid si el padre no está en el arreglo
        foreach ($usersById as &$user) {
            if ($user['pid'] !== null && !isset($usersById[$user['pid']])) {
                $user['pid'] = null;
            }
        }
        unset($user);

        // 3. Contar cuántos nodos raíz hay (pid == null)
        $rootCount = 0;
        foreach ($usersById as $user) {
            if ($user['pid'] === null) {
                $rootCount++;
            }
        }

        // 4. Si hay más de uno, usar nodo raíz fake
        if ($rootCount > 1) {
            $rootUser = [
                'id'      => 10000001,
                'name'    => "Organigrama Personalizado",
                'title'   => null,
                'pid'     => null,
                'img'     => base_url('assets/images/logos/logo-2.png'),
                'ghost'   => false,
                'niveles' => 0,
                'children'=> []
            ];

            $usersById = [10000001 => $rootUser] + $usersById;

            foreach ($usersById as &$user) {
                if ($user['id'] != 10000001 && $user['pid'] === null) {
                    $user['pid'] = 10000001;
                }
            }
            unset($user);
        }

        // 5. Construir el árbol
        foreach ($usersById as &$user) {
            if ($user['pid'] !== null && isset($usersById[$user['pid']])) {
                $usersById[$user['pid']]['children'][] = &$user;
            }
        }
        unset($user);

        // 6. Devolver el nodo raíz
        if (isset($usersById[10000001])) {
            return $usersById[10000001];
        } else {
            foreach ($usersById as $user) {
                if ($user['pid'] === null) {
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * Obtener datos del organigrama en formato árbol
     */
    public function getOrganigramChartData($organigramaId)
    {
        $users = $this->getUsersByOrganigrama($organigramaId);
        
        if (empty($users)) {
            return null;
        }

        return $this->buildOrganizationTree($users);
    }
}
