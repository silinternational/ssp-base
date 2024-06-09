<?php

use common\helpers\MySqlDateTime;
use yii\db\Migration;

class m991231_235959_insert_test_users extends Migration
{

    public function safeUp()
    {
        $now = MySqlDateTime::now();
        $later = MySqlDateTime::relative('+1 month');
        $earlier = MySqlDateTime::relative('-1 days');
        $this->batchInsert('{{user}}', 
            ['id', 'employee_id', 'username', 'email', 'require_mfa', 'review_profile_after', 'nag_for_mfa_after', 'nag_for_method_after', 'manager_email', 'first_name', 'last_name', 'last_changed_utc', 'last_synced_utc', 'active', 'locked', 'uuid' ], [
                [  1, '10001', 'distant_future', '10001@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '2b2d424e-8cb0-49c7-8c0b-7f660340f5fa'],
                [  2, '10002', 'near_future', '10002@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'ef960c92-09fc-44f4-aadf-2d3aea6e0dbd'],
                [  3, '10003', 'next_day', '10003@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'a42317a0-9a43-4da0-9921-50f004e011c0'],
                [  4, '10004', 'already_past', '10004@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '7bab90d3-9f54-4187-804d-7f6400021789'],
                [  5, '10005', 'no_review', '10005@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '6b614606-bbe8-4793-b0db-ca862295c661'],
                [  6, '10006', 'mfa_add', '10006@example.com', 'no', $later, $earlier, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '7c695eac-dbca-45d0-b3dc-2df2e1d2294c'],
                [  7, '10007', 'method_add', '10007@example.com', 'no', $later, $later, $earlier, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'cc0f9920-c77a-4b73-aee3-04393b79d925'],
                [  8, '10008', 'profile_review', '10008@example.com', 'no', $earlier, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '0a003cc9-f831-4985-820a-55a1022c7fd9'],
                [  9, '10009', 'no_mfa_needed', '10009@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'c818d44a-a322-45f4-a1d0-6afc3c2a54e9'],
                [ 10, '10010', 'must_set_up_mfa', '10010@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '40c4428d-3f4d-42d5-a0f9-3c5bfd8f890a'],
                [ 11, '10011', 'has_backupcode', '10011@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '24b9308f-7f7b-41b9-85bb-84cfd496506b'],
                [ 12, '10012', 'has_backupcode_and_mgr', '10012@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'b3e0b85c-e154-44aa-93c2-d278784834f6'],
                [ 13, '10013', 'has_totp', '10013@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '0a3c54b1-5b28-4477-8f01-040d438260cc'],
                [ 14, '10014', 'has_totp_and_mgr', '10014@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '4f5524f4-ab47-455d-a8cc-74f46c15af4a'],
                [ 15, '10015', 'has_webauthn', '10015@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'd64e97bd-4f11-4b50-ad6e-009c999986d2'],
                [ 16, '10016', 'has_webauthn_and_mgr', '10016@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '7f9e0b84-e880-43ff-9e73-47017a55726b'],
                [ 17, '10017', 'has_all', '10017@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '0f16cab4-e670-4479-8ba1-0e32f29c5541'],
                [ 18, '10018', 'has_rate_limited_mfa', '10018@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'ede9498f-8fbe-4734-b999-f611bad5c17e'],
                [ 19, '10019', 'has_4_backupcodes', '10019@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '540f72b3-2a12-40df-8487-519ba30252bd'],
                [ 20, '10020', 'has_1_backupcode_only', '10020@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '3408bea9-c183-4c07-9a2b-477cfa6d9d27'],
                [ 21, '10021', 'has_1_backupcode_plus', '10021@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '44112a40-b53b-4dd1-a805-c7db1dded8ea'],
                [ 22, '10022', 'has_webauthn_totp', '10022@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'ed03cb38-a463-47db-859c-27303eb73ad2'],
                [ 23, '10023', 'has_webauthn_totp_and_mgr', '10023@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '0446e441-73e5-4319-bf2b-0d9b397f63ad'],
                [ 24, '10024', 'has_webauthn_backupcodes', '10024@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'aed1bad5-a14b-4f2f-bf02-03389c55333d'],
                [ 25, '10025', 'has_webauthn_backupcodes_and_mgr', '10025@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '46809f3b-fa5e-4eb0-a878-81d52df3d653'],
                [ 26, '10026', 'has_webauthn_totp_backupcodes', '10026@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '70195b2c-e42b-48e2-ae22-fd957bf5ffb7'],
                [ 27, '10027', 'has_webauthn_totp_backupcodes_and_mgr', '10027@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'e5f8a7b8-667b-48ef-9e0f-358a37d9f44e'],
                [ 28, '10028', 'has_mgr_code_webauthn_and_more_recently_used_totp', '10028@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '8f3cdf9a-4da9-49c9-92ab-692b2cf4a9b0'],
                [ 29, '10029', 'has_webauthn_and_more_recently_used_totp', '10029@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'f47d3013-82d7-4576-b963-50368ee6aef5'],
                [ 30, '10030', 'has_totp_and_more_recently_used_webauthn', '10030@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '550b0747-cebe-4d36-95a5-6684ebd7510c'],
                [ 31, '10031', 'has_totp_and_more_recently_used_backup_code', '10031@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '95a983a9-723c-48e8-8c1a-c83a65926ec7'],
                [ 32, '10032', 'has_backup_code_and_more_recently_used_totp', '10032@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '8290587f-67ef-4c74-914a-8c78fd46cace'],
                [ 33, '10033', 'has_totp_backupcodes', '10033@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'ac72e145-e2dc-42d6-aa89-efccca617ac5'],
                [ 34, '10034', 'has_totp_backupcodes_and_mgr', '10034@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', 'b05baa03-cd3d-4947-9a3a-9f3065350aea'],
                [ 35, '10035', 'has_mgr_code', '10035@example.com', 'yes', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '28b366cc-c5b9-4bf3-bd37-1a8e8ca73a66'],
                [ 36, '10036', 'sildisco_idp1', '10036@example.com', 'no', $later, $later, $later, 'mgr@example.com', '', '', $now, $now, 'yes', 'no', '06974223-d832-4938-8923-5c598e4446b3'],
            ]);

        $tomorrow = MySqlDateTime::relative('+1 days');
        $soon = MySqlDateTime::relative('+3 days');
        $nextYear = MySqlDateTime::relative('+1 year');
        $past = MySqlDateTime::relative('-1 month');
        $recent = MySqlDateTime::relative('-1 day');
        $this->batchInsert('{{password}}',
            ['id', 'user_id', 'created_utc', 'expires_on', 'grace_period_ends_on', 'hash'], [
                [  1,  1, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [  2,  2, $now, $soon, $soon, password_hash('b', PASSWORD_BCRYPT)],
                [  3,  3, $now, $tomorrow, $tomorrow, password_hash('b', PASSWORD_BCRYPT)],
                [  4,  4, $now, $past, $tomorrow, password_hash('c', PASSWORD_BCRYPT)],
                [  5,  5, $now, $nextYear, $nextYear, password_hash('e', PASSWORD_BCRYPT)],
                [  6,  6, $now, $nextYear, $nextYear, password_hash('f', PASSWORD_BCRYPT)],
                [  7,  7, $now, $nextYear, $nextYear, password_hash('g', PASSWORD_BCRYPT)],
                [  8,  8, $now, $nextYear, $nextYear, password_hash('h', PASSWORD_BCRYPT)],
                [  9,  9, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 10, 10, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 11, 11, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 12, 12, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 13, 13, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 14, 14, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 15, 15, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 16, 16, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 17, 17, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 18, 18, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 19, 19, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 20, 20, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 21, 21, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 22, 22, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 23, 23, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 24, 24, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 25, 25, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 26, 26, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 27, 27, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 28, 28, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 29, 29, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 30, 30, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 31, 31, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 32, 32, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 33, 33, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 34, 34, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 35, 35, $now, $nextYear, $nextYear, password_hash('a', PASSWORD_BCRYPT)],
                [ 36, 36, $now, $nextYear, $nextYear, password_hash('sildisco_password', PASSWORD_BCRYPT)],
            ]);

        for ($i = 0; $i < 37; $i++) {
            $this->update('{{user}}', ['current_password_id' => $i], 'id=' . $i);
        }

        //TODO: unfortunately, a real uuid that's been verified is required for manual testing. It may be possible
        // to decouple 2-factor config from authentication, or to make a test mock for this purpose.
        $this->batchInsert('{{mfa}}',
            ['id', 'user_id', 'type', 'last_used_utc', 'external_uuid', 'label', 'verified', 'created_utc'], [
                [ 1, 11, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 2, 12, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 3, 13, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 4, 14, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 5, 15, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 6, 16, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 7, 17, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 8, 17, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 9, 17, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 10, 18, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 11, 19, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 12, 20, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 13, 21, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 14, 21, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 15, 22, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 16, 22, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 17, 23, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 18, 23, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 19, 24, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 20, 24, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 21, 25, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 22, 25, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 23, 26, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 24, 26, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 25, 26, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 26, 27, 'webauthn', NULL, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 27, 27, 'totp', NULL, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 28, 27, 'backupcode', NULL, NULL, 'Printable Codes', 1, $now],
                [ 29, 28, 'webauthn', $past, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 30, 28, 'totp', $recent, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 31, 28, 'manager', $past, NULL, 'Manager Backup Code', 1, $now],
                [ 32, 29, 'webauthn', $past, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 33, 29, 'totp', $recent, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 34, 30, 'webauthn', $recent, '6092a08c-b271-4971-996a-6577333a7b6d', 'Security Key', 1, $now],
                [ 35, 30, 'totp', $past, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 36, 31, 'totp', $past, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 37, 31, 'backupcode', $recent, NULL, 'Printable Codes', 1, $now],
                [ 38, 32, 'totp', $recent, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 39, 32, 'backupcode', $past, NULL, 'Printable Codes', 1, $now],
                [ 40, 33, 'totp', $past, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 41, 33, 'backupcode', $past, NULL, 'Printable Codes', 1, $now],
                [ 42, 34, 'totp', $past, '38764a89-b904-404e-a195-1ad2bcfabf75', 'Smartphone App', 1, $now], // JVRXKYTMPBEVKXLS
                [ 43, 34, 'backupcode', $past, NULL, 'Printable Codes', 1, $now],
                [ 44, 35, 'manager', $past, NULL, 'Manager Backup Code', 1, $now],
                [ 45, 35, 'backupcode', $past, NULL, 'Printable Codes', 1, $now],
            ]);

        $this->batchInsert('{{mfa_backupcode}}',
            ['id', 'mfa_id', 'value', 'created_utc'        ], [
                [ 1, 1, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 2, 1, '$2y$10$JsiRI/W/FLfZzJLPj8umKeXP.rvsOW4aYQO5mOEOwGkBPpKhKWT2K', $now], // 01970541
                [ 3, 1, '$2y$10$rA5MdrbEcmbCiqtAgPXnYeBCEKc.AnylPArnamyu.x4DS/A0/0/4i', $now], // 77802769
                [ 4, 1, '$2y$10$If6srqyKGBag/x.nPDBeau9bjNR1RZgxqRVKhdRhJk2PkbOn5rKNS', $now], // 82743523
                [ 5, 1, '$2y$10$NWw0.DPBSm.bjQoSck8xbeqJgENUhE/WazmHmsEtWoxs/UKaIdkUq', $now], // 37771076
                [ 6, 2, '$2y$10$If6srqyKGBag/x.nPDBeau9bjNR1RZgxqRVKhdRhJk2PkbOn5rKNS', $now], // 82743523
                [ 7, 7, '$2y$10$rA5MdrbEcmbCiqtAgPXnYeBCEKc.AnylPArnamyu.x4DS/A0/0/4i', $now], // 77802769
                [ 8, 10, '$2y$10$JsiRI/W/FLfZzJLPj8umKeXP.rvsOW4aYQO5mOEOwGkBPpKhKWT2K', $now], // 01970541
                [ 9, 11, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 10, 11, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 11, 11, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 12, 11, '$2y$10$rA5MdrbEcmbCiqtAgPXnYeBCEKc.AnylPArnamyu.x4DS/A0/0/4i', $now], // 77802769
                [ 13, 12, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 14, 13, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 15, 19, '$2y$10$rA5MdrbEcmbCiqtAgPXnYeBCEKc.AnylPArnamyu.x4DS/A0/0/4i', $now], // 77802769
                [ 16, 21, '$2y$10$JsiRI/W/FLfZzJLPj8umKeXP.rvsOW4aYQO5mOEOwGkBPpKhKWT2K', $now], // 01970541
                [ 17, 25, '$2y$10$NWw0.DPBSm.bjQoSck8xbeqJgENUhE/WazmHmsEtWoxs/UKaIdkUq', $now], // 37771076
                [ 18, 28, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 19, 37, '$2y$10$If6srqyKGBag/x.nPDBeau9bjNR1RZgxqRVKhdRhJk2PkbOn5rKNS', $now], // 82743523
                [ 20, 39, '$2y$10$rA5MdrbEcmbCiqtAgPXnYeBCEKc.AnylPArnamyu.x4DS/A0/0/4i', $now], // 77802769
                [ 21, 41, '$2y$10$JsiRI/W/FLfZzJLPj8umKeXP.rvsOW4aYQO5mOEOwGkBPpKhKWT2K', $now], // 01970541
                [ 22, 43, '$2y$10$NWw0.DPBSm.bjQoSck8xbeqJgENUhE/WazmHmsEtWoxs/UKaIdkUq', $now], // 37771076
                [ 23, 44, '$2y$10$j/V6zcotFES8MkVmgRaiMe2E6DV1qjmO8UhUoJQD0/.p6LhZddGn2', $now], // 94923279
                [ 24, 45, '$2y$10$NWw0.DPBSm.bjQoSck8xbeqJgENUhE/WazmHmsEtWoxs/UKaIdkUq', $now], // 37771076
            ]);

        $this->batchInsert('{{mfa_failed_attempt}}',
            ['id', 'mfa_id', 'at_utc'], [
                [ 1, 10, $now],
                [ 2, 10, $now],
                [ 3, 10, $now],
                [ 4, 10, $now],
                [ 5, 10, $now],
            ]);

        $this->batchInsert('{{method}}',
            ['id', 'uid', 'user_id', 'value', 'verified', 'created'            ], [
                [ 1, 1, 8, 'method_08@example.com', 1, $now],
            ]);
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
