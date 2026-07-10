<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo de USUARIOS: cuentas que inician sesión en la intranet.
 *
 * Los usuarios con rol `user` (viewers) se administran desde Nexus vía API
 * (identificados por `nexus_id`). Las cuentas `admin`/`operator` se crean de
 * forma manual desde la sección Usuarios. Este modelo NO tiene datos de
 * organigrama; eso vive en EmployeeModel.
 */
class UserModel extends Model
{
    protected $table              = 'users';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['name', 'lastname', 'email', 'password', 'nexus_id', 'rol', 'active', 'photo', 'last_login'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Listado de cuentas de login (para la sección Usuarios del admin).
     */
    public function getUsers($id = null)
    {
        if ($id !== null) {
            return $this->find($id);
        }

        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserByNexusId($nexusId)
    {
        return $this->where('nexus_id', $nexusId)->first();
    }

    /**
     * Crea una cuenta de login. $data ya debe traer el password hasheado.
     */
    public function createUser(array $data)
    {
        return $this->insert($data);
    }

    public function setLoginUpdate($id)
    {
        return $this->update($id, [
            'last_login' => date("Y-m-d H:i:s"),
        ]);
    }

    public function setNewPassword($id, $password)
    {
        return $this->update($id, [
            'password' => $password,
        ]);
    }

    public function setNewPhoto($id, $photo)
    {
        return $this->update($id, [
            'photo' => $photo,
        ]);
    }

    public function updateProfile($id, $name, $lastname)
    {
        return $this->update($id, [
            'name'     => $name,
            'lastname' => $lastname,
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
}
