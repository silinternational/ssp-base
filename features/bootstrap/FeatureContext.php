<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

class FeatureContext implements Context
{
    private const HUB_URL = 'http://hub/module.php/core/authenticate.php?as=hub-discovery';
    
    /** @var Client */
    private $client;
    
    /** @var ResponseInterface */
    private $response;
    
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @When I go to the Hub
     */
    public function iGoToTheHub()
    {
        $this->response = $this->client->get(self::HUB_URL);
    }

    /**
     * @Then I should see our material theme
     */
    public function iShouldSeeOurMaterialTheme()
    {
        $responseContent = $this->response->getBody()->getContents();
        Assert::contains(
            $responseContent,
            '<base href="http://hub/module.php/material/">',
            'Failed to find the expected evidence of our material theme'
        );
    }
}
