<?php

namespace SimpleSAML\Module\silauth\Auth\Source\migrations;

use yii\db\Migration;

class M170214145629RemoveOldTables extends Migration
{
    public function safeUp(): void
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

    public function safeDown(): bool
    {
        echo "M170214145629RemoveOldTables cannot be reverted.\n";

        return false;
    }
}
