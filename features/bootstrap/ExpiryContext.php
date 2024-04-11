<?php
namespace Sil\SspExpiryChecker\Behat\context;

use Behat\Behat\Context\Context;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class ExpiryContext implements Context
{
    protected $username = null;
    protected $password = null;
    
    /**
     * The browser session, used for interacting with the website.
     *
     * @var Session
     */
    protected $session;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $driver = new GoutteDriver();
        $this->session = new Session($driver);
        $this->session->start();
    }
    
    /**
     * Assert that the given page has a form that contains the given text.
     *
     * @param string $text The text (or HTML) to search for.
     * @param DocumentElement $page The page to search in.
     * @return void
     */
    protected function assertFormContains($text, $page)
    {
        $forms = $page->findAll('css', 'form');
        foreach ($forms as $form) {
            if (strpos($form->getHtml(), $text) !== false) {
                return;
            }
        }
        Assert::fail(sprintf(
            "No form found containing %s in this HTML:\n%s",
            var_export($text, true),
            $page->getHtml()
        ));
    }
    
    /**
     * Assert that the given page does NOT have a form that contains the given
     * text.
     *
     * @param string $text The text (or HTML) to search for.
     * @param DocumentElement $page The page to search in.
     * @return void
     */
    protected function assertFormNotContains($text, $page)
    {
        $forms = $page->findAll('css', 'form');
        foreach ($forms as $form) {
            if (strpos($form->getHtml(), $text) !== false) {
                Assert::fail(sprintf(
                    "Found a form containing %s in this HTML:\n%s",
                    var_export($text, true),
                    $page->getHtml()
                ));
            }
        }
    }
    
    /**
     * Get the login button from the given page.
     *
     * @param DocumentElement $page The page.
     * @return NodeElement
     */
    protected function getLoginButton($page)
    {
        $buttons = $page->findAll('css', 'button');
        $loginButton = null;
        foreach ($buttons as $button) {
            $lcButtonText = strtolower($button->getText());
            if (strpos($lcButtonText, 'login') !== false) {
                $loginButton = $button;
                break;
            }
        }
        Assert::assertNotNull($loginButton, 'Failed to find the login button');
        return $loginButton;
    }

    /**
     * @Given I provide credentials that will expire in the distant future
     */
    public function iProvideCredentialsThatWillExpireInTheDistantFuture()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'distant_future';
        $this->password = 'a';
    }

    /**
     * @When I login
     */
    public function iLogin()
    {
        $this->session->visit('http://sp/module.php/core/authenticate.php?as=ssp-hub-idp');
        $page = $this->session->getPage();
        $page->fillField('username', $this->username);
        $page->fillField('password', $this->password);
        $this->submitLoginForm($page);
    }

    /**
     * @Then I should end up at my intended destination
     */
    public function iShouldEndUpAtMyIntendedDestination()
    {
        $page = $this->session->getPage();
        Assert::assertContains('Your attributes', $page->getHtml());
    }

    /**
     * @Given I provide credentials that will expire very soon
     */
    public function iProvideCredentialsThatWillExpireVerySoon()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'near_future';
        $this->password = 'b';
    }

    /**
     * @Then I should see a warning that my password will expire soon
     */
    public function iShouldSeeAWarningThatMyPasswordWillExpireSoon()
    {
        $page = $this->session->getPage();
        Assert::assertContains('will expire', $page->getHtml());
    }
    
    /**
     * Submit the login form, including the secondary page's form (if
     * simpleSAMLphp shows another page because JavaScript isn't supported).
     *
     * @param DocumentElement $page The page.
     */
    protected function submitLoginForm($page)
    {
        $loginButton = $this->getLoginButton($page);
        $loginButton->click();
        
        // SimpleSAMLphp 1.15 markup for secondary page:
        $postLoginSubmitButton = $page->findButton('postLoginSubmitButton');
        if ($postLoginSubmitButton instanceof NodeElement) {
            $postLoginSubmitButton->click();
        } else {
            
            // SimpleSAMLphp 1.14 markup for secondary page:
            $body = $page->find('css', 'body');
            if ($body instanceof NodeElement) {
                $onload = $body->getAttribute('onload');
                if ($onload === "document.getElementsByTagName('input')[0].click();") {
                    $body->pressButton('Submit');
                }
            }
        }
    }

    /**
     * @Then there should be a way to go change my password now
     */
    public function thereShouldBeAWayToGoChangeMyPasswordNow()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('change', $page);
    }

    /**
     * @Then there should be a way to continue without changing my password
     */
    public function thereShouldBeAWayToContinueWithoutChangingMyPassword()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('continue', $page);
    }

    /**
     * @Given I provide credentials that have expired
     */
    public function iProvideCredentialsThatHaveExpired()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'already_past';
        $this->password = 'c';
    }

    /**
     * @Then I should see a message that my password has expired
     */
    public function iShouldSeeAMessageThatMyPasswordHasExpired()
    {
        $page = $this->session->getPage();
        Assert::assertContains('has expired', $page->getHtml());
    }

    /**
     * @Then there should NOT be a way to continue without changing my password
     */
    public function thereShouldNotBeAWayToContinueWithoutChangingMyPassword()
    {
        $page = $this->session->getPage();
        $this->assertFormNotContains('continue', $page);
    }

    /**
     * @Given I provide credentials that have no password expiration date
     */
    public function iProvideCredentialsThatHaveNoPasswordExpirationDate()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'missing_exp';
        $this->password = 'd';
    }

    /**
     * @Then I should see an error message
     */
    public function iShouldSeeAnErrorMessage()
    {
        $page = $this->session->getPage();
        Assert::assertContains('An error occurred', $page->getHtml());
    }

    /**
     * @Given I provide credentials that have an invalid password expiration date
     */
    public function iProvideCredentialsThatHaveAnInvalidPasswordExpirationDate()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'invalid_exp';
        $this->password = 'e';
    }
}
