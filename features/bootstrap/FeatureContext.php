<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;

class FeatureContext implements Context
{
    /** @var Client */
    private $client;
    
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @When I go to the Hub
     */
    public function iGoToTheHub()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see our material theme
     */
    public function iShouldSeeOurMaterialTheme()
    {
        throw new PendingException();
    }
}
