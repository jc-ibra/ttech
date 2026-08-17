<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Configuración administrable de la intranet.
 *
 * - `settings`: almacén clave-valor para interruptores de módulos (por ejemplo,
 *   mostrar u ocultar los organigramas o el bloque de "Sistemas externos").
 *   Los valores por defecto viven en SettingModel::defaults(), así que la tabla
 *   puede quedar vacía y todo se comporta como antes.
 * - `external_systems`: enlaces del bloque "Sistemas externos" (antes estaban
 *   hardcodeados en las vistas de /profile y /trantor-informa). Se siembran los
 *   tres accesos actuales para que nada cambie visualmente tras migrar.
 */
class CreateSettingsAndExternalSystemsTables extends Migration
{
    public function up()
    {
        // --- settings -------------------------------------------------------
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('setting_key');
        $this->forge->createTable('settings');

        // --- external_systems ----------------------------------------------
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('external_systems');

        // Semilla: los accesos que hasta ahora estaban hardcodeados en las vistas.
        $now = date('Y-m-d H:i:s');
        $this->db->table('external_systems')->insertBatch([
            [
                'title'       => 'Help Desk',
                'description' => 'Levanta y da seguimiento a tus tickets de soporte.',
                'icon'        => 'ti ti-ticket',
                'url'         => 'https://helpdesk.trantortechnologies.mx/',
                'sort_order'  => 1,
                'active'      => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'title'       => 'Documentación de uso GLPI',
                'description' => 'Guias y documentacion para el uso de GLPI',
                'icon'        => 'ti ti-book',
                'url'         => 'https://docs.helpdesk.trantortechnologies.mx/',
                'sort_order'  => 2,
                'active'      => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'title'       => 'Correo Staff',
                'description' => 'Accede a tu correo corporativo.',
                'icon'        => 'ti ti-mail',
                'url'         => 'https://mail.staff.trantortechnologies.mx/',
                'sort_order'  => 3,
                'active'      => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('external_systems');
        $this->forge->dropTable('settings');
    }
}
