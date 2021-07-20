<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use Webmozart\Assert\Assert;

class FeatureContext implements Context
{
    private const HUB_DISCO_URL = 'http://hub/module.php/core/authenticate.php?as=hub-discovery';
    
    /** @var DocumentElement|null */
    private $page;
    
    /** @var Session */
    private $session;
    
    public function __construct()
    {
        $driver = new GoutteDriver();
        $this->session = new Session($driver);
        // See http://mink.behat.org/en/latest/guides/session.html for docs.
        $this->session->start();
    }

    /**
     * @When I go to the Hub's discovery page
     */
    public function iGoToTheHubsDiscoveryPage()
    {
        $this->goToPage(self::HUB_DISCO_URL);
    }
    
    private function goToPage(string $url)
    {
        $this->session->visit($url);
        $this->page = $this->session->getPage();
    }

    /**
     * @Then I should see our material theme
     */
    public function iShouldSeeOurMaterialTheme()
    {
        $hasMaterialDesignElement = $this->page->has('css', '.mdl-layout');
        Assert::true(
            $hasMaterialDesignElement,
            'Failed to find the expected evidence of our material theme'
        );
    }
}
