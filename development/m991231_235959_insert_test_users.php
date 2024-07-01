<?php

use common\helpers\MySqlDateTime;
use yii\db\Migration;

class m991231_235959_insert_test_users extends Migration
{

    public function safeUp()
    {
        $now = MySqlDateTime::now();
        $later = MySqlDateTime::relative('+1 month');
        $users = [
            [1, '10001', 'sildisco_idp2', '10001@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '06974223-d832-4938-8923-5c598e4446b3'],
        ];
        $this->batchInsert('{{user}}',
            ['id', 'employee_id', 'username', 'email', 'require_mfa', 'review_profile_after', 'nag_for_mfa_after', 'nag_for_method_after', 'manager_email', 'first_name', 'last_name', 'last_changed_utc', 'last_synced_utc', 'active', 'locked', 'uuid'],
            $users);

        $nextYear = MySqlDateTime::relative('+1 year');
        $passwords = [1, 1, $now, $nextYear, $nextYear, password_hash('sildisco_password', PASSWORD_BCRYPT)];
        $this->batchInsert('{{password}}',
            ['id', 'user_id', 'created_utc', 'expires_on', 'grace_period_ends_on', 'hash'], [
                $passwords,
            ]);

        for ($i = 1; $i <= count($users); $i++) {
            $this->update('{{user}}', ['current_password_id' => $i], 'id=' . $i);
        }
    }

    public function safeDown()
    {
        $this->delete('{{email_log}}');
        $this->delete('{{mfa_backupcode}}');
        $this->delete('{{mfa_failed_attempt}}');
        $this->delete('{{mfa}}');
        $this->delete('{{method}}');
        $this->delete('{{user}}');
        $this->delete('{{password}}');

    }
}
