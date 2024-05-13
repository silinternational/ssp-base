<?php

namespace Sil\SilAuth\migrations;

use yii\db\Migration;

class M161213135750CreateInitialTables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{user}}', [
            'id' => 'pk',
            'uuid' => 'string NOT NULL',
            'employee_id' => 'string NOT NULL',
            'first_name' => 'string NOT NULL',
            'last_name' => 'string NOT NULL',
            'username' => 'string NOT NULL',
            'email' => 'string NOT NULL',
            'password_hash' => 'string NULL',
            'active' => "enum('Yes','No') NOT NULL DEFAULT 'Yes'",
            'locked' => "enum('No','Yes') NOT NULL DEFAULT 'No'",
            'login_attempts' => 'integer NOT NULL DEFAULT 0',
            'block_until' => 'datetime NULL',
            'last_updated' => 'datetime NOT NULL',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci');
        $this->createIndex('uq_user_uuid', '{{user}}', 'uuid', true);
        $this->createIndex('uq_user_employee_id', '{{user}}', 'employee_id', true);
        $this->createIndex('uq_user_username', '{{user}}', 'username', true);
        $this->createIndex('uq_user_email', '{{user}}', 'email', true);
        
        $this->createTable('{{previous_password}}', [
            'id' => 'pk',
            'user_id' => 'integer NOT NULL',
            'password_hash' => 'string NOT NULL',
            'created' => 'datetime NOT NULL',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci');
        $this->addForeignKey(
            'fk_prev_pw_user_user_id',
            '{{previous_password}}',
            'user_id',
            '{{user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_prev_pw_user_user_id',
            '{{previous_password}}'
        );
        $this->dropTable('{{previous_password}}');
        
        $this->dropIndex('uq_user_uuid', '{{user}}');
        $this->dropIndex('uq_user_employee_id', '{{user}}');
        $this->dropIndex('uq_user_username', '{{user}}');
        $this->dropIndex('uq_user_email', '{{user}}');
        $this->dropTable('{{user}}');
    }
}
