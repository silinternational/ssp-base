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
}
