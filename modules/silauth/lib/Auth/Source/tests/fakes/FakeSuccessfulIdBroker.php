<?php
namespace SimpleSAML\Module\silauth\Auth\Source\tests\fakes;

use GuzzleHttp\Psr7\Response;

class FakeSuccessfulIdBroker extends FakeIdBroker
{
    public function getAuthenticatedUser(string $username, string $password): ?array
    {
        $this->logger->info('FAKE SUCCESS: accepting {username} and {password}.', [
            'username' => var_export($username, true),
            'password' => var_export($password, true),
        ]);
        return parent::getAuthenticatedUser($username, $password);
    }
    
    protected function getDesiredResponse(): Response
    {
        return new Response(200, [], json_encode([
            'uuid' => '11111111-aaaa-1111-aaaa-111111111111',
            'employee_id' => '123',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'display_name' => 'John Smith',
            'username' => 'john_smith',
            'email' => 'john_smith@example.com',
            'locked' => 'no',
            'mfa' => [
                'prompt' => 'no',
                'add' => 'no',
                'review' => 'no',
                'options' => [],
            ],
            'method' => [
                'add' => 'no',
                'review' => 'no',
                'options' => [],
            ],
        ]));
    }
}
