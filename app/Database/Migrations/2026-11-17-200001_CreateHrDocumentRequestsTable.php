<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Local mirror of Nexus HR document requests for Mi espacio / alerts.
 * Source of truth remains Nexus; this table is for UX and upload forwarding.
 */
class CreateHrDocumentRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nexus_request_id'=> ['type' => 'INT', 'unsigned' => true],
            'nexus_id'        => ['type' => 'VARCHAR', 'constraint' => 32],
            'user_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'document_key'    => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'document_name'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'status'          => ['type' => 'VARCHAR', 'constraint' => 32, 'default' => 'requested'],
            'notes'           => ['type' => 'TEXT', 'null' => true],
            'rejection_reason'=> ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nexus_request_id');
        $this->forge->addKey('nexus_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('hr_document_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('hr_document_requests', true);
    }
}
