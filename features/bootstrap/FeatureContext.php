<?php

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;
use Webmozart\Assert\Assert;

class FeatureContext implements Context
{
    private const HUB_BAD_AUTH_SOURCE_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=wrong';
    private const HUB_DISCO_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=hub-discovery';
    private const HUB_HOME_URL = 'http://ssp-hub.local';
    
    /** @var Session */
    private $session;
    
    public function __construct()
    {
        $driver = new ChromeDriver('http://test-browser:9222', null, 'http://ssp-hub.local');
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
    }

    /**
     * @Then I should see our material theme
     */
    public function iShouldSeeOurMaterialTheme()
    {
        $page = $this->session->getPage();
        $hasMaterialDesignElement = $page->has('css', '.mdl-layout');
        Assert::true(
            $hasMaterialDesignElement,
            'Failed to find the expected evidence of our material theme'
        );
    }

    /**
     * @When I go to the Hub's home page
     */
    public function iGoToTheHubsHomePage()
    {
        $this->goToPage(self::HUB_HOME_URL);
    }

    /**
     * @When I click on :linkText
     */
    public function iClickOn($linkText)
    {
        $page = $this->session->getPage();
        $page->clickLink($linkText);
    }

    /**
     * @When I log in as a hub administrator
     */
    public function iLogInAsAHubAdministrator()
    {
        $page = $this->session->getPage();
        
        $usernameField = $page->findField('username');
        Assert::notNull($usernameField, 'Could not find the username field');
        $usernameField->setValue('admin');
        
        $passwordField = $page->findField('password');
        Assert::notNull($passwordField, 'Could not find the password field');
        $passwordField->setValue('abc123');
        
        $loginButton = $page->findButton('Login');
        Assert::notNull($loginButton, 'Could not find the login button');
        $loginButton->click();
    }

    /**
     * @When I go to the Hub but specify an invalid authentication source
     */
    public function iGoToTheHubButSpecifyAnInvalidAuthenticationSource()
    {
        $this->goToPage(self::HUB_BAD_AUTH_SOURCE_URL);
    }

    /**
     * @Then I should see a(n) :title page
     */
    public function iShouldSeeAPage($title)
    {
        $page = $this->session->getPage();
        $titleElement = $page->find('css', 'head > title');
        Assert::notNull($titleElement, "Could not find the page's title");
        if ($titleElement->getText() !== $title) {
            throw new Exception(sprintf(
                "This does not seem to be a(n) %s page:\n%s",
                $title,
                $page->getHtml()
            ));
        }
    }

    /**
     * @When I click on the :idpName tile
     */
    public function iClickOnTheTile($idpName)
    {
        $page = $this->session->getPage();
        $idpTileTitle = sprintf('Login with your %s identity account', $idpName);
        $idpTile = $page->find(
            'css',
            sprintf('div[title="%s"]', $idpTileTitle)
        );
        Assert::notNull($idpTile, 'Failed to find ' . $idpName . ' tile');
        $button = $idpTile->find('css', 'button');
        Assert::notNull($button, 'Failed to find button for ' . $idpName);
        $button->click();
    }
}
