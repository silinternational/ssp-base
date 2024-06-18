<?php
namespace SimpleSAML\Module\silauth\Auth\Source\tests\fakes;

use GuzzleHttp\Psr7\Response;

class FakeInvalidIdBroker extends FakeIdBroker
{
    public function getAuthenticatedUser(string $username, string $password): ?array
    {
        $this->logger->info('FAKE ERROR: invalid/unexpected response.');
        return parent::getAuthenticatedUser($username, $password);
    }
    
    protected function getDesiredResponse(): Response
    {
        return new Response(404);
    }
}
