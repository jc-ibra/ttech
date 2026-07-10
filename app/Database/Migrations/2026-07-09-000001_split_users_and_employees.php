<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Separa el concepto "empleado" (nodo de organigrama) del concepto
 * "usuario" (cuenta que inicia sesión en la intranet).
 *
 *  - La tabla actual `users` (que contenía toda la data de organigrama) se
 *    renombra a `employees`.
 *  - Se crea una nueva tabla `users` mínima para las cuentas de acceso
 *    (login), administrada desde Nexus vía API para el rol `user` y de forma
 *    manual para `admin`/`operator`.
 *  - Las cuentas admin/operator existentes se copian a la nueva tabla `users`
 *    para que no pierdan el acceso.
 */
class SplitUsersAndEmployees extends Migration
{
    public function up()
    {
        $db     = $this->db;
        $dbName = $db->getDatabase();

        // 1. Eliminar TODAS las claves foráneas que referencian a `users`
        //    (custom_organigrama_users.user_id/parent_id, custom_organigramas.created_by
        //     y cualquier auto-referencia de la propia tabla users).
        $fks = $db->query(
            "SELECT TABLE_NAME, CONSTRAINT_NAME
               FROM information_schema.KEY_COLUMN_USAGE
              WHERE REFERENCED_TABLE_NAME = 'users'
                AND TABLE_SCHEMA = ?",
            [$dbName]
        )->getResultArray();

        foreach ($fks as $fk) {
            $db->query("ALTER TABLE `{$fk['TABLE_NAME']}` DROP FOREIGN KEY `{$fk['CONSTRAINT_NAME']}`");
        }

        // 2. Renombrar la tabla de organigrama: users -> employees
        $db->query('RENAME TABLE `users` TO `employees`');

        // 3. Crear la nueva tabla `users` para cuentas de acceso (login)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'lastname' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            // Identificador del usuario en el sistema externo Nexus.
            'nexus_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'rol' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'user',
            ],
            'active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'assets/images/anonimo.jpg',
            ],
            'last_login' => [
                'type' => 'DATETIME',
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('nexus_id');
        $this->forge->createTable('users');

        // 4. Sembrar las cuentas admin/operator existentes en la nueva tabla
        //    para conservar su acceso. Los viewers (rol user) los repuebla Nexus.
        $db->query(
            "INSERT INTO `users` (name, lastname, email, password, rol, active, photo, nexus_id, last_login, created_at, updated_at)
             SELECT e.name, e.lastname, e.email, e.password, e.rol, e.active,
                    COALESCE(NULLIF(e.photo, ''), 'assets/images/anonimo.jpg'),
                    NULL, e.last_login, NOW(), NOW()
               FROM `employees` e
              WHERE e.rol IN ('admin', 'operator')
                AND (e.ghost IS NULL OR e.ghost = 0)
                AND e.deleted_at IS NULL"
        );

        // 5. Reasignar la autoría de contenidos (feed, comentarios, sugerencias
        //    y organigramas) a las nuevas cuentas de login. Los ids antiguos
        //    apuntaban a la tabla combinada (hoy `employees`); se remapean al
        //    nuevo id de `users` por correo cuando la persona tiene cuenta de
        //    login. Los autores sin cuenta de login quedan sin remapear (se
        //    resuelven con LEFT JOIN en los modelos).
        $remaps = [
            ['table' => 'feed',                 'column' => 'author'],
            ['table' => 'feed_comments',        'column' => 'author'],
            ['table' => 'suggestions',          'column' => 'author'],
            ['table' => 'custom_organigramas',  'column' => 'created_by'],
        ];

        foreach ($remaps as $remap) {
            $t = $remap['table'];
            $c = $remap['column'];

            if (!$db->tableExists($t)) {
                continue;
            }

            $db->query(
                "UPDATE `{$t}` x
                   JOIN `employees` e ON e.id = x.`{$c}`
                   JOIN `users` u ON u.email = e.email
                    SET x.`{$c}` = u.id
                  WHERE x.`{$c}` IS NOT NULL"
            );
        }
    }

    public function down()
    {
        // Best-effort: eliminar la tabla de login y devolver employees -> users.
        $this->forge->dropTable('users', true);
        $this->db->query('RENAME TABLE `employees` TO `users`');
    }
}
