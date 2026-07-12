<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Contenido administrable de la landing "Trantor Technologies".
 * Almacén clave-valor: cada campo editable (texto o ruta de imagen) es una fila.
 * Los valores por defecto viven en LandingContentModel::defaults(), por lo que
 * la tabla puede quedar vacía y la página seguirá renderizando el contenido base.
 */
class CreateLandingContentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'content_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'content_value' => [
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
        $this->forge->addUniqueKey('content_key');
        $this->forge->createTable('landing_content');
    }

    public function down()
    {
        $this->forge->dropTable('landing_content');
    }
}
