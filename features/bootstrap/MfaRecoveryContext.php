<?php

use Behat\Step\When;
use Behat\Mink\Element\DocumentElement;
use Behat\Step\Given;
use Behat\Step\Then;
use PHPUnit\Framework\Assert;
use Sil\PhpEnv\Env;
use SimpleSAML\Module\mfa\Auth\Process\Mfa;

/**
 * Defines application features from the specific context.
 */
class MfaRecoveryContext extends MfaContext
{
    #[Given('I use an IDP that is configured to offer MFA recovery-contacts')]
    public function iUseAnIdpThatIsConfiguredToOfferMfaRecoveryContacts(): void
    {
        $this->iClickOnTheTile('IDP 4');
    }

    #[Then('I should see a link to send a code to a recovery contact')]
    public function iShouldSeeALinkToSendACodeToARecoveryContact(): void
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            '/module.php/mfa/send-recovery-mfa.php',
            $page->getContent()
        );
    }

    #[Then('I should see a way to send an MFA recovery code to my manager')]
    public function iShouldSeeAWayToSendAnMfaRecoveryCodeToMyManager(): void
    {
        $page = $this->session->getPage();
        Assert::assertNotNull(
            $page->findById('option-manager'),
            'Did not find a way to select my manager'
        );

        $this->assertHasSendButton($page);
    }

    protected function assertHasSendButton(DocumentElement $page): void
    {
        Assert::assertTrue(
            $page->hasButton('send'),
            'Did not find a "send" button'
        );
    }

    #[Then('I should see a way to send an MFA recovery code to another recovery contact')]
    public function iShouldSeeAWayToSendAnMfaRecoveryCodeToAnotherRecoveryContact(): void
    {
        $page = $this->session->getPage();
        $foundNonManagerOption = false;
        $contactOptionElements = $page->findAll('css', 'input[name=mfaRecoveryContactID]');
        foreach ($contactOptionElements as $element) {
            $elementID = $element->getAttribute('id');
            if ($elementID !== 'option-manager') {
                $foundNonManagerOption = true;
                break;
            }
        }
        Assert::assertTrue(
            $foundNonManagerOption,
            'Did not find a way to select another recovery contact'
        );

        $this->assertHasSendButton($page);
    }

    #[When('I send the code to the recovery contact')]
    public function iSendTheCodeToTheRecoveryContact(): void
    {
        $this->selectARecoveryContactOption();
        $this->submitFormByClickingButtonNamed('send');
    }

    protected function selectARecoveryContactOption(): void
    {
        $page = $this->session->getPage();
        $contactOptionElements = $page->findAll('css', 'input[name=mfaRecoveryContactID]');
        foreach ($contactOptionElements as $element) {
            $elementID = $element->getAttribute('id');
            if ($elementID !== 'option-manager') {
                $element->click();
                return;
            }
        }
        Assert::fail('Failed to find a way to select a (non-manager) recovery contact');
    }

    #[Then('I should not see an error message')]
    public function iShouldNotSeeAnErrorMessage(): void
    {
        $page = $this->session->getPage();
        Assert::assertNotContains(
            'error',
            $page->getContent()
        );
    }

    #[Then('I should see confirmation that the code was sent')]
    public function iShouldSeeConfirmationThatTheCodeWasSent(): void
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'A temporary code was sent to your recovery contact.',
            $page->getContent()
        );
    }

    #[Given('the recovery-contacts API has at least one contact for that account')]
    public function theRecoveryContactsApiHasAtLeastOneContactForThatAccount(): void
    {
        $fakeState = [
            'recoveryConfig' => [
                'api' => Env::requireEnv('MFA_RECOVERY_CONTACTS_API'),
                'apiKey' => Env::requireEnv('MFA_RECOVERY_CONTACTS_API_KEY'),
                'fallbackEmail' => Env::requireEnv('MFA_RECOVERY_CONTACTS_FALLBACK_NAME'),
                'fallbackName' => Env::requireEnv('MFA_RECOVERY_CONTACTS_FALLBACK_EMAIL'),
            ],
            'Attributes' => [
                /* This test email address should match the email address in
                 * authsources.php for the user this test scenario logged in as. */
                'mail' => 'has_backupcode@example.com',
            ],
        ];

        $recoveryContacts = Mfa::getRecoveryContactsByName($fakeState);

        Assert::assertNotEmpty($recoveryContacts, sprintf(
            'No recovery contacts returned by %s for %s',
            $fakeState['recoveryConfig']['api'],
            $fakeState['Attributes']['mail']
        ));
    }
}
