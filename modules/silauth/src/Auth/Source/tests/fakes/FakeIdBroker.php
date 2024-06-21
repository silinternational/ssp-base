<?php

namespace SimpleSAML\Module\silauth\Auth\Source\tests\fakes;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Psr\Log\LoggerInterface;
use SimpleSAML\Module\silauth\Auth\Source\auth\Authenticator;
use Sil\Idp\IdBroker\Client\IdBrokerClient;
use SimpleSAML\Module\silauth\Auth\Source\auth\IdBroker;

abstract class FakeIdBroker extends IdBroker
{
    public function __construct(
        string          $baseUri,
        string          $accessToken,
        LoggerInterface $logger,
        string          $idpDomainName = 'fake.example.com'
    )
    {
        parent::__construct(
            $baseUri,
            $accessToken,
            $logger,
            $idpDomainName,
            [],
            false
        );

        // Now replace the client with one that will return the desired response.
        $this->client = new IdBrokerClient($baseUri, $accessToken, [
            'http_client_options' => [
                'handler' => HandlerStack::create(new MockHandler(

                /* Set up several, since this may be called multiple times
                 * during a test: */
                    array_fill(
                        0,
                        Authenticator::BLOCK_AFTER_NTH_FAILED_LOGIN * 2,
                        $this->getDesiredResponse()
                    )
                )),
            ],
            IdBrokerClient::ASSERT_VALID_BROKER_IP_CONFIG => false,
        ]);
    }

    abstract protected function getDesiredResponse();
}
