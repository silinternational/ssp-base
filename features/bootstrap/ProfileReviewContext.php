<?php
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use PHPUnit\Framework\Assert;
use Sil\PhpEnv\Env;

/**
 * Defines application features from the specific context.
 */
class ProfileReviewContext extends FeatureContext
{
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
     * Submit the current form, including the secondary page's form (if
     * simpleSAMLphp shows another page because JavaScript isn't supported) by
     * clicking the specified button.
     *
     * @param string $buttonName The value of the desired button's `name`
     *     attribute.
     */
    protected function submitFormByClickingButtonNamed($buttonName)
    {
        $page = $this->session->getPage();
        $button = $page->find('css', sprintf(
            '[name=%s]',
            $buttonName
        ));
        Assert::assertNotNull($button, 'Failed to find button named ' . $buttonName);
        $button->click();
        $this->submitSecondarySspFormIfPresent($page);
    }

    /**
     * @Given I provide credentials that do not need review
     */
    public function iProvideCredentialsThatDoNotNeedReview()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'no_review';
        $this->password = 'e';
    }

    /**
     * @Given I provide credentials that are due for a(n) :category :nagType reminder
     */
    public function iProvideCredentialsThatAreDueForAReminder($category, $nagType)
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = $category . '_' . $nagType;
        switch ($this->username) {
            case 'mfa_add':
                $this->password = 'f';
                break;

            case 'method_add':
                $this->password = 'g';
                break;

            case 'profile_review':
                $this->password = 'h';
                break;
        }
    }


    protected function pageContainsElementWithText($cssSelector, $text)
    {
        $page = $this->session->getPage();
        $elements = $page->findAll('css', $cssSelector);
        foreach ($elements as $element) {
            if (strpos($element->getText(), $text) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Then there should be a way to continue to my intended destination
     */
    public function thereShouldBeAWayToContinueToMyIntendedDestination()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="continue"', $page);
    }

    /**
     * @When I click the remind-me-later button
     */
    public function iClickTheRemindMeLaterButton()
    {
        $this->submitFormByClickingButtonNamed('continue');
    }

    /**
     * @When I click the update profile button
     */
    public function iClickTheUpdateProfileButton()
    {
        $this->submitFormByClickingButtonNamed('update');
    }

    /**
     * @Then I should end up at the update profile URL
     */
    public function iShouldEndUpAtTheUpdateProfileUrl()
    {
        $profileUrl = Env::get('PROFILE_URL_FOR_TESTS');
        Assert::assertNotEmpty($profileUrl, 'No PROFILE_URL_FOR_TESTS provided');
        $currentUrl = $this->session->getCurrentUrl();
        Assert::assertStringStartsWith(
            $profileUrl,
            $currentUrl,
            'Did NOT end up at the update profile URL'
        );
    }

    /**
     * @Then I should see the message: :message
     */
    public function iShouldSeeTheMessage($message)
    {
        $page = $this->session->getPage();
        Assert::assertContains($message, $page->getHtml());
    }

    /**
     * @Then there should be a way to go update my profile now
     */
    public function thereShouldBeAWayToGoUpdateMyProfileNow()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="update"', $page);
    }

    /**
     * @Given I provide credentials for a user that has used the manager mfa option
     */
    public function iProvideCredentialsForAUserThatHasUsedTheManagerMfaOption()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'profile_review';
        $this->password = 'h';
    }

    /**
     * @Then I should not see any manager mfa information
     */
    public function iShouldNotSeeAnyManagerMfaInformation()
    {
        $page = $this->session->getPage();
        $isManagerMfaPresent = $page->hasContent('manager');
        Assert::assertFalse($isManagerMfaPresent, 'found manager mfa data');
    }
}
