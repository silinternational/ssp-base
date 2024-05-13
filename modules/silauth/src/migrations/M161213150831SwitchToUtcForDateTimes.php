<?php

namespace Sil\SilAuth\migrations;

use yii\db\Migration;

class M161213150831SwitchToUtcForDateTimes extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('{{user}}', 'block_until', 'block_until_utc');
        $this->renameColumn('{{user}}', 'last_updated', 'last_updated_utc');
        $this->renameColumn('{{previous_password}}', 'created', 'created_utc');
    }

    public function safeDown()
    {
        $this->renameColumn('{{previous_password}}', 'created_utc', 'created');
        $this->renameColumn('{{user}}', 'last_updated_utc', 'last_updated');
        $this->renameColumn('{{user}}', 'block_until_utc', 'block_until');
    }
}
