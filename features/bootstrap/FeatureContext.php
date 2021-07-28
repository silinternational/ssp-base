<?php

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkContext;
use DMore\ChromeDriver\ChromeDriver;
use Webmozart\Assert\Assert;

class FeatureContext extends MinkContext
{
    private const HUB_BAD_AUTH_SOURCE_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=wrong';
    private const HUB_DISCO_URL = 'http://ssp-hub.local/module.php/core/authenticate.php?as=hub-discovery';
    private const HUB_HOME_URL = 'http://ssp-hub.local';
    private const SP1_LOGIN_PAGE = 'http://ssp-hub-sp.local/module.php/core/authenticate.php?as=ssp-hub';
    
    /** @var Session */
    private $session;
    
    public function __construct()
    {
        $driver = new ChromeDriver('http://test-browser:9222', null, 'http://ssp-hub.local');
        $this->session = new Session($driver);
        $mink = new Mink(['default' => $this->session]);
        $mink->setDefaultSessionName('default');
        $this->setMink($mink);
        // See http://mink.behat.org/en/latest/guides/session.html for docs.
        $this->session->start();
    }

    /** @AfterStep */
    public function afterStep(Behat\Behat\Hook\Scope\AfterStepScope $scope)
    {
        if (! $scope->getTestResult()->isPassed()) {
            $this->showPageDetails();
        }
    }
    
    private function showPageDetails()
    {
        echo '[' . $this->session->getStatusCode() . '] ';
        $this->printLastResponse();
    }

    /**
     * @When I go to the Hub's discovery page
     */
    public function iGoToTheHubsDiscoveryPage()
    {
        $this->visit(self::HUB_DISCO_URL);
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
        $this->visit(self::HUB_HOME_URL);
    }

    /**
     * @When I click on :linkText
     */
    public function iClickOn($linkText)
    {
        $this->clickLink($linkText);
    }

    /**
     * @When I log in as a hub administrator
     */
    public function iLogInAsAHubAdministrator()
    {
        $this->logInAs('admin', 'abc123');
    }

    private function logInAs(string $username, string $password)
    {
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Login');
    }

    /**
     * @When I go to the Hub but specify an invalid authentication source
     */
    public function iGoToTheHubButSpecifyAnInvalidAuthenticationSource()
    {
        $this->visit(self::HUB_BAD_AUTH_SOURCE_URL);
    }

    /**
     * @Then I should see a(n) :title page
     */
    public function iShouldSeeAPage($title)
    {
        $page = $this->session->getPage();
        $titleElement = $page->find('css', 'head > title');
        Assert::notNull($titleElement, "Could not find the page's title");
        Assert::same(
            $titleElement->getText(),
            $title,
            "This does not seem to be a '$title' page"
        );
    }
    
    private function stringContainsString($haystack, $needle): bool
    {
        return strpos($haystack, $needle) !== false;
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
        $button->press();
    }

    /**
     * @When I go to the SP1 login page
     */
    public function iGoToTheSp1LoginPage()
    {
        $this->visit(self::SP1_LOGIN_PAGE);
    }

    /**
     * @When I log in as a user who's password is NOT about to expire
     */
    public function iLogInAsAUserWhosPasswordIsNotAboutToExpire()
    {
        $this->logInAs('distant_future', 'a');
    }

    /**
     * @Then I should see a page indicating that I successfully logged in
     */
    public function iShouldSeeAPageIndicatingThatISuccessfullyLoggedIn()
    {
        $this->assertResponseStatus(200);
        $this->assertPageBodyContainsText('Your attributes');
    }
    
    private function assertPageBodyContainsText(string $expectedText)
    {
        $page = $this->session->getPage();
        $body = $page->find('css', 'body');
        Assert::contains($body->getText(), $expectedText);
    }

    /**
     * @When I log in as a user who's password is about to expire
     */
    public function iLogInAsAUserWhosPasswordIsAboutToExpire()
    {
        $this->logInAs('near_future', 'a');
    }

    /**
     * @Then I should see a page warning me that my password is about to expire
     */
    public function iShouldSeeAPageWarningMeThatMyPasswordIsAboutToExpire()
    {
        $this->assertPageBodyContainsText('Password expiring soon');
    }
}
