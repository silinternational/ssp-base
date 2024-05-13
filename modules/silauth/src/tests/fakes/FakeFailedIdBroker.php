<?php
namespace Sil\SilAuth\tests\fakes;

use GuzzleHttp\Psr7\Response;

class FakeFailedIdBroker extends FakeIdBroker
{
    public function getAuthenticatedUser(string $username, string $password)
    {
        $this->logger->info('FAKE FAILURE: rejecting {username} and {password}.', [
            'username' => var_export($username, true),
            'password' => var_export($password, true),
        ]);
        return parent::getAuthenticatedUser($username, $password);
    }
    
    protected function getDesiredResponse()
    {
        return new Response(400);
    }
}
