<?php

use Behat\Step\Given;
use Behat\Step\Then;
use PHPUnit\Framework\Assert;

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
        Assert::assertTrue(
            $page->hasButton('send'),
            'Did not find a "send" button'
        );
    }
}
