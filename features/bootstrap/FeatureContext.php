<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use Webmozart\Assert\Assert;

class FeatureContext implements Context
{
    private const HUB_BAD_AUTH_SOURCE_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=wrong';
    private const HUB_DISCO_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=hub-discovery';
    
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

    /**
     * @When I go to the Hub but specify an invalid authentication source
     */
    public function iGoToTheHubButSpecifyAnInvalidAuthenticationSource()
    {
        $this->goToPage(self::HUB_BAD_AUTH_SOURCE_URL);
    }

    /**
     * @Then I should see an error page
     */
    public function iShouldSeeAnErrorPage()
    {
        $titleElement = $this->page->find('css', '.mdl-layout-title');
        Assert::notNull($titleElement, 'Could not find the title text element');
        $titleText = $titleElement->getText();
        Assert::same($titleText, 'Error', 'This does not seem to be an error page');
    }
}
