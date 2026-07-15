<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Agrega la columna `employee_number` a la tabla `users`.
 *
 * Nexus (aprovisionamiento) ahora envía el número de empleado en el campo
 * `numero_empleado` de la API v1. Lo guardamos para reutilizarlo en otras
 * partes de la intranet. Es opcional: los usuarios existentes pueden no
 * tenerlo todavía, por eso admite NULL.
 *
 * No es llave de recurso: los usuarios se siguen identificando por `nexus_id`.
 */
class AddEmployeeNumberToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'employee_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'default'    => null,
                'after'      => 'nexus_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'employee_number');
    }
}
