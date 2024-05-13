<?php

namespace Sil\SilAuth\migrations;

use yii\db\Migration;

class M170215141724SplitFailedLoginsTable extends Migration
{
    public function safeUp()
    {
        // Remove old indexes.
        $this->dropIndex('idx_failed_logins_ip_address', '{{failed_logins}}');
        $this->dropIndex('idx_failed_logins_username', '{{failed_logins}}');
        
        // Split/update table and add new indexes.
        $this->dropColumn('{{failed_logins}}', 'ip_address');
        $this->renameTable('{{failed_logins}}', '{{failed_login_username}}');
        $this->createIndex(
            'idx_failed_login_username_username',
            '{{failed_login_username}}',
            'username',
            false
        );
        /* The max length needed to store an IP address is 45 characters. See
         * http://stackoverflow.com/a/1076755/3813891 for details.  */
        $this->createTable('{{failed_login_ip_address}}', [
            'id' => 'pk',
            'ip_address' => 'varchar(45) NOT NULL',
            'occurred_at_utc' => 'datetime NOT NULL',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci');
        $this->createIndex(
            'idx_failed_login_ip_address_ip_address',
            '{{failed_login_ip_address}}',
            'ip_address',
            false
        );
    }

    public function safeDown()
    {
        echo "M170215141724SplitFailedLoginsTable cannot be reverted.\n";
        return false;
    }
}
