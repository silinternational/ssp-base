<?php

use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Logger;
use SimpleSAML\Module;
use SimpleSAML\Module\expirychecker\Auth\Process\ExpiryDate;
use SimpleSAML\Module\expirychecker\Utilities;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\XHTML\Template;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$state = State::loadState($stateId, 'expirychecker:about2expire');

/* Skip the splash pages for awhile, both to let the user get to the
 * change-password website and to avoid annoying them with constant warnings. */
ExpiryDate::skipSplashPagesFor(14400); // 14400 seconds = 4 hours

if (array_key_exists('continue', $_REQUEST)) {

    // The user has pressed the continue button.
    ProcessingChain::resumeProcessing($state);
}

if (array_key_exists('changepwd', $_REQUEST)) {

    // The user has pressed the change-password button.
    $passwordChangeUrl = $state['passwordChangeUrl'];

    // Add the original url as a parameter
    if (array_key_exists('saml:RelayState', $state)) {
        $stateId = State::saveState(
            $state,
            'expirychecker:about2expire'
        );

        $returnTo = Utilities::getUrlFromRelayState(
            $state['saml:RelayState']
        );
        if (! empty($returnTo)) {
            $passwordChangeUrl .= '?returnTo=' . $returnTo;
        }
    }

    HTTP::redirectTrustedURL($passwordChangeUrl, array());
}

$globalConfig = Configuration::getInstance();

$t = new Template($globalConfig, 'expirychecker:about2expire.php');
$t->data['formTarget'] = Module::getModuleURL('expirychecker/about2expire.php');
$t->data['formData'] = ['StateId' => $stateId];
$t->data['daysLeft'] = $state['daysLeft'];
$t->data['dayOrDays'] = (intval($state['daysLeft']) === 1 ? 'day' : 'days');
$t->data['expiresAtTimestamp'] = $state['expiresAtTimestamp'];
$t->data['accountName'] = $state['accountName'];
$t->show();

Logger::info('expirychecker - User has been warned that their password will expire soon.');
