<?php
namespace Sil\SilAuth\tests\fakes;

use GuzzleHttp\Psr7\Response;

class FakeInvalidIdBroker extends FakeIdBroker
{
    public function getAuthenticatedUser(string $username, string $password)
    {
        $this->logger->info('FAKE ERROR: invalid/unexpected response.');
        return parent::getAuthenticatedUser($username, $password);
    }
    
    protected function getDesiredResponse()
    {
        return new Response(404);
    }
}
