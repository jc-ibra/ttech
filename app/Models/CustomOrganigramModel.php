<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomOrganigramModel extends Model
{
    protected $table              = 'custom_organigramas';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['name', 'description', 'created_by', 'active', 'show_in_general'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Obtener todos los organigramas
     */
    public function getOrganigramas($id = null)
    {
        // created_by referencia una cuenta de login (users). LEFT JOIN para no
        // ocultar organigramas cuyo creador ya no exista en la tabla de login.
        $this->join('users', 'users.id = custom_organigramas.created_by', 'left')
             ->select('custom_organigramas.*, CONCAT(users.name, " ", users.lastname) as creator_name');
        
        if ($id !== null) {
            return $this->find($id);
        }

        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Crear un nuevo organigrama
     */
    public function createOrganigrama($name, $description, $created_by, $show_in_general = 0)
    {
        $data = [
            'name'             => $name,
            'description'      => $description,
            'created_by'       => $created_by,
            'show_in_general'  => $show_in_general,
        ];

        return $this->insert($data);
    }

    /**
     * Actualizar un organigrama
     */
    public function updateOrganigrama($id, $name, $description, $show_in_general = 0)
    {
        return $this->update($id, [
            'name'             => $name,
            'description'      => $description,
            'show_in_general'  => $show_in_general,
        ]);
    }

    /**
     * Eliminar un organigrama
     */
    public function deleteOrganigrama($id)
    {
        return $this->delete($id);
    }

    /**
     * Activar/Desactivar organigrama
     */
    public function toggleActive($id, $active)
    {
        return $this->update($id, ['active' => $active]);
    }

    /**
     * Obtener organigramas marcados como "mostrar en general"
     */
    public function getGeneralOrganigramas()
    {
        return $this->where('show_in_general', 1)
                    ->where('active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
