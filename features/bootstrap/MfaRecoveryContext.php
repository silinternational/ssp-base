<?php

namespace Sil\SspBase\Features\bootstrap;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Behat\Tester\Exception\PendingException;
use MfaContext;

/**
 * Defines application features from the specific context.
 */
class MfaRecoveryContext extends MfaContext
{
    #[Given('we have recovery-contacts config')]
    public function weHaveRecoveryContactsConfig(): void
    {
        throw new PendingException();
    }

    #[Then('I should see a link to send a code to a recovery contact')]
    public function iShouldSeeALinkToSendACodeToARecoveryContact(): void
    {
        throw new PendingException();
    }
}
