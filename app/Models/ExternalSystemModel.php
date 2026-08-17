<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Enlaces del bloque "Sistemas externos" (Help Desk, GLPI, Correo Staff, …).
 * Antes estaban hardcodeados en las vistas; ahora se administran desde
 * /configuracion. Si la tabla no existe todavía (migración pendiente) se
 * devuelven los defaults, que son exactamente los tres accesos originales.
 */
class ExternalSystemModel extends Model
{
    protected $table          = 'external_systems';
    protected $primaryKey     = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = ['title', 'description', 'icon', 'url', 'sort_order', 'active'];

    /**
     * Accesos originales, usados como respaldo si la tabla aún no existe.
     */
    public static function defaults(): array
    {
        return [
            (object) [
                'id'          => 0,
                'title'       => 'Help Desk',
                'description' => 'Levanta y da seguimiento a tus tickets de soporte.',
                'icon'        => 'ti ti-ticket',
                'url'         => 'https://helpdesk.trantortechnologies.mx/',
                'sort_order'  => 1,
                'active'      => 1,
            ],
            (object) [
                'id'          => 0,
                'title'       => 'Documentación de uso GLPI',
                'description' => 'Guias y documentacion para el uso de GLPI',
                'icon'        => 'ti ti-book',
                'url'         => 'https://docs.helpdesk.trantortechnologies.mx/',
                'sort_order'  => 2,
                'active'      => 1,
            ],
            (object) [
                'id'          => 0,
                'title'       => 'Correo Staff',
                'description' => 'Accede a tu correo corporativo.',
                'icon'        => 'ti ti-mail',
                'url'         => 'https://mail.staff.trantortechnologies.mx/',
                'sort_order'  => 3,
                'active'      => 1,
            ],
        ];
    }

    /**
     * Todos los sistemas (activos e inactivos), para el administrador.
     */
    public function getSystems($id = null)
    {
        try {
            if ($id !== null) {
                return $this->find($id);
            }

            return $this->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
        } catch (\Throwable $e) {
            return $id !== null ? null : self::defaults();
        }
    }

    /**
     * Sólo los sistemas visibles, en el orden configurado.
     */
    public function getActiveSystems(): array
    {
        try {
            return $this->where('active', 1)
                        ->orderBy('sort_order', 'ASC')
                        ->orderBy('id', 'ASC')
                        ->findAll();
        } catch (\Throwable $e) {
            return self::defaults();
        }
    }
}
