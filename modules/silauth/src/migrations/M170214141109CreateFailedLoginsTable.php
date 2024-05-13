<?php

namespace Sil\SilAuth\migrations;

use yii\db\Migration;

class M170214141109CreateFailedLoginsTable extends Migration
{
    public function safeUp()
    {
        /* The max length needed to store an IP address is 45 characters. See
         * http://stackoverflow.com/a/1076755/3813891 for details.  */
        $this->createTable('{{failed_logins}}', [
            'id' => 'pk',
            'username' => 'string NOT NULL',
            'ip_address' => 'varchar(45) NOT NULL',
            'occurred_at_utc' => 'datetime NOT NULL',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci');
        $this->createIndex(
            'idx_failed_logins_username',
            '{{failed_logins}}',
            'username',
            false
        );
        $this->createIndex(
            'idx_failed_logins_ip_address',
            '{{failed_logins}}',
            'ip_address',
            false
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx_failed_logins_ip_address', '{{failed_logins}}');
        $this->dropIndex('idx_failed_logins_username', '{{failed_logins}}');
        $this->dropTable('{{failed_logins}}');
    }
}
