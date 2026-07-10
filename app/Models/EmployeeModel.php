<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo de EMPLEADOS: nodos que se muestran y ordenan en los organigramas.
 * Los empleados NO inician sesión; las cuentas de acceso viven en UserModel.
 */
class EmployeeModel extends Model{

    protected $table              = 'employees';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['name', 'lastname', 'email', 'password', 'last_login', 'active', 'photo', 'parent', 'rol', 'ocupation', 'telephone', 'email_secondary', 'cellphone', 'ext', 'date_entry', 'date_discharge', 'employee_number', 'hide_emails', 'show_in_directory', 'ghost', 'has_ghost', 'real_parent', 'department', 'niveles', 'area', 'reingreso'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getUsers($id = null)
    {

        $this->join('ocupations', 'ocupations.id = employees.ocupation')
             ->join('employees as parent', 'parent.id = employees.parent', 'left')
             ->join('employees as real_parent', 'real_parent.id = employees.real_parent', 'left')
             ->join('departments', 'departments.id = employees.department', 'left')
             ->join('areas', 'areas.id = employees.area', 'left')
             ->select('employees.*, ocupations.name as ocupation_name, CONCAT(parent.name, " ", parent.lastname) as parent_name, CONCAT(employees.name, " ", employees.lastname) as complete_name, CONCAT(real_parent.name, " ", real_parent.lastname) as real_parent_complete_name, departments.name as department_name, areas.name as area_name');
        if($id !== null){
            return $this->find($id);
        }

        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getDirectory()
    {

        $this->join('ocupations', 'ocupations.id = employees.ocupation')
             ->join('employees as parent', 'parent.id = employees.parent', 'left')
             ->select('employees.*, ocupations.name as ocupation_name, CONCAT(parent.name, " ", parent.lastname) as parent_name, CONCAT(employees.name, " ", employees.lastname) as complete_name')
             ->where('employees.active', 1)
             ->where('employees.show_in_directory', 1);

        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function createUser($name, $lastname, $email, $password, $photo, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area)
    {
        $data = [
            'name'        => $name,
            'lastname'    => $lastname,
            'email'       => $email,
            'password'    => $password,
            'photo'       => $photo,
            'telephone'   => $telephone,
            'rol'         => $rol,
            'ocupation'   => $ocupation,
            'department'  => $department,
            'parent'      => $parent,
            'email_secondary' => $email_secondary,
            'cellphone'   => $cellphone,
            'ext'         => $ext,
            'date_entry'  => $date_entry,
            'employee_number' => $employee_number,
            'hide_emails' => $hide_emails,
            'show_in_directory' => $show_in_directory,
            'ghost'       => $ghost,
            'has_ghost'   => $has_ghost,
            'real_parent' => $real_parent,
            'niveles'     => $niveles,
            'area'        => $area,
        ];

        return $this->insert($data);
    }

    public function setNewParent($id, $parent)
    {
        return $this->update($id, [
            'parent' => $parent,
        ]);
    }

    public function deleteGhost($id)
    {
        return $this->delete($id, true);
    }

    public function setNewPhoto($id, $photo)
    {
        return $this->update($id, [
            'photo'       => $photo,
        ]);
    }

    public function updateUser($id, $name, $lastname, $email, $photo, $telephone, $rol, $ocupation, $department, $parent, $email_secondary, $cellphone, $ext, $date_entry, $date_discharge, $employee_number, $hide_emails, $show_in_directory, $ghost, $has_ghost, $real_parent, $niveles, $area)
    {
        return $this->update($id, [
            'name'        => $name,
            'lastname'    => $lastname,
            'email'       => $email,
            'photo'       => $photo,
            'telephone'   => $telephone,
            'rol'         => $rol,
            'ocupation'   => $ocupation,
            'department'  => $department,
            'parent'      => $parent,
            'email_secondary' => $email_secondary,
            'cellphone'   => $cellphone,
            'ext'         => $ext,
            'date_entry'  => $date_entry,
            'date_discharge' => $date_discharge,
            'employee_number' => $employee_number,
            'hide_emails' => $hide_emails,
            'show_in_directory' => $show_in_directory,
            'ghost'       => $ghost,
            'has_ghost'   => $has_ghost,
            'real_parent' => $real_parent,
            'niveles'     => $niveles,
            'area'        => $area
        ]);
    }

    public function activeUser($id)
    {
        return $this->update($id, [
            'active' => 1,
        ]);
    }

    public function inactiveUser($id)
    {
        return $this->update($id, [
            'active' => 0,
        ]);
    }

    public function reingresarUsuario($id, $date_entry)
    {
        return $this->update($id, [
            'date_entry' => $date_entry,
            'reingreso'  => 1,
            'active'     => 1,
            'date_discharge' => null,
        ]);
    }


    public function getOrganization()
    {
        $users = $this->join('ocupations', 'ocupations.id = employees.ocupation')
                      ->select('employees.id, CONCAT(employees.name, " ", employees.lastname) as name, ocupations.name as title, employees.parent as pid, employees.photo')
                      ->findAll();

        return array_map(function($user) {
            return [
                'id'    => $user->id,
                'Puesto' => $user->title,
                'pid'   => $user->pid,
                'Nombre'  => $user->name,
                'img'   => $user->photo,
            ];
        }, $users);
    }

    private function buildOrganizationTree($users, $forceFakeRoot = false)
    {
        // 1. Construir arreglo asociativo
        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user->id] = [
                'id'      => $user->id,
                'name'    => $user->name,
                'title'   => $user->title,
                'pid'     => $user->pid, // temporal, se ajusta después
                'img'     => base_url($user->photo),
                'ghost'   => $user->ghost == 1 ? true : false,
                'niveles' => $user->niveles,
                'children'=> []
            ];
        }

        // 2. Ajustar pid si el padre no está en el arreglo
        foreach ($usersById as &$user) {
            if ($user['pid'] !== null && !isset($usersById[$user['pid']])) {
                $user['pid'] = null;
            }
        }
        unset($user); // Rompe la referencia

        // 3. Contar cuántos nodos raíz hay (pid == null)
        $rootCount = 0;
        foreach ($usersById as $user) {
            if ($user['pid'] === null) {
                $rootCount++;
            }
        }

        // 4. Si hay más de uno, o se fuerza, usar nodo raíz fake
        if ($rootCount > 1 || $forceFakeRoot) {
            $rootUser = [
                'id'      => 10000001,
                'name'    => "Trantor Technologies",
                'title'   => null,
                'pid'     => null,
                'img'     => base_url('assets/images/logos/logo-2.png'),
                'ghost'   => false,
                'ghost_p' => true,
                'niveles' => null,
                'children'=> []
            ];

            if (!isset($usersById[10000001])) {
                $usersById = [10000001 => $rootUser] + $usersById;
            }

            // Asignar el root fake como padre a los nodos raíz
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

        // 6. Ajuste: si el root fake tiene hijos ghost, colapsarlos
        if (isset($usersById[10000001])) {
            $rootId = 10000001;
            $root = &$usersById[$rootId];

            $newChildren = [];
            foreach ($root['children'] as &$child) {
                if (!empty($child['ghost'])) {
                    $lifted = [];
                    $this->collectNonGhostDescendants($child, $lifted);
                    foreach ($lifted as $liftedNode) {
                        $liftedNode['pid'] = $rootId;
                        $newChildren[] = $liftedNode;
                    }
                } else {
                    $newChildren[] = $child;
                }
            }
            unset($child);

            $root['children'] = $newChildren;

            return $root;
        } else {
            // Solo uno, buscar el nodo raíz real
            foreach ($usersById as $user) {
                if ($user['pid'] === null) {
                    return $user;
                }
            }
        }
        return null; // Por si muere
    }

    private function collectNonGhostDescendants($node, &$result)
    {
        if (empty($node['ghost'])) {
            $result[] = $node;
            return;
        }

        foreach ($node['children'] as $child) {
            $this->collectNonGhostDescendants($child, $result);
        }
    }

    private function fetchUsersByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        return $this->db->table($this->table)
            ->join('ocupations', 'ocupations.id = employees.ocupation')
            ->select('employees.id, CONCAT(employees.name, " ", employees.lastname) as name, ocupations.name as title, employees.parent as pid, employees.photo, employees.ghost, employees.niveles')
            ->whereIn('employees.id', $ids)
            ->where('employees.active', 1)
            ->get()
            ->getResult();
    }

    private function addAncestorsToUsers($users)
    {
        $usersById = [];
        $pendingParentIds = [];

        foreach ($users as $user) {
            $usersById[$user->id] = $user;
            if ($user->pid !== null) {
                $pendingParentIds[$user->pid] = true;
            }
        }

        foreach (array_keys($usersById) as $existingId) {
            unset($pendingParentIds[$existingId]);
        }

        while (!empty($pendingParentIds)) {
            $idsToFetch = array_keys($pendingParentIds);
            $pendingParentIds = [];

            $parents = $this->fetchUsersByIds($idsToFetch);
            foreach ($parents as $parent) {
                if (!isset($usersById[$parent->id])) {
                    $usersById[$parent->id] = $parent;
                    if ($parent->pid !== null && !isset($usersById[$parent->pid])) {
                        $pendingParentIds[$parent->pid] = true;
                    }
                }
            }
        }

        return array_values($usersById);
    }

    private function markNonFilteredAsGhost($users, $visibleIds)
    {
        foreach ($users as $user) {
            if (!isset($visibleIds[$user->id])) {
                $user->ghost = 1;
            }
        }

        return $users;
    }

    public function getOrganizationChart()
    {
        $users = $this->join('ocupations', 'ocupations.id = employees.ocupation')
            ->select('employees.id, CONCAT(employees.name, " ", employees.lastname) as name, ocupations.name as title, employees.parent as pid, employees.photo, employees.ghost, employees.niveles')
            ->where('employees.active', 1)
            ->findAll();

        return $this->buildOrganizationTree($users);
    }

    public function getOrganizationChartByDepartment($department)
    {
        $users = $this->join('ocupations', 'ocupations.id = employees.ocupation')
            ->select('employees.id, CONCAT(employees.name, " ", employees.lastname) as name, ocupations.name as title, employees.parent as pid, employees.photo, employees.ghost, employees.niveles')
            ->where('employees.active', 1)
            ->where('employees.department', $department)
            ->findAll();

        $visibleIds = array_flip(array_map(function($user) {
            return $user->id;
        }, $users));

        $users = $this->addAncestorsToUsers($users);
        $users = $this->markNonFilteredAsGhost($users, $visibleIds);

        return $this->buildOrganizationTree($users, true);
    }

    public function getOrganizationChartByArea($area)
    {
        $users = $this->join('ocupations', 'ocupations.id = employees.ocupation')
            ->select('employees.id, CONCAT(employees.name, " ", employees.lastname) as name, ocupations.name as title, employees.parent as pid, employees.photo, employees.ghost, employees.niveles')
            ->where('employees.active', 1)
            ->where('employees.area', $area)
            ->findAll();

        $visibleIds = array_flip(array_map(function($user) {
            return $user->id;
        }, $users));

        $users = $this->addAncestorsToUsers($users);
        $users = $this->markNonFilteredAsGhost($users, $visibleIds);

        return $this->buildOrganizationTree($users, true);
    }
}
