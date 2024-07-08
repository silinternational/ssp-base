<?php

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

$state = State::loadState($stateId, 'expirychecker:expired');

if (array_key_exists('changepwd', $_REQUEST)) {

    /* Now that they've clicked change-password, skip the splash pages very
     * briefly, to let the user get to the change-password website.  */
    ExpiryDate::skipSplashPagesFor(60); // 60 seconds = 1 minute

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
        if (!empty($returnTo)) {
            $passwordChangeUrl .= '?returnTo=' . $returnTo;
        }
    }

    $httpUtils = new HTTP();
    $httpUtils->redirectTrustedURL($passwordChangeUrl, array());
}

$globalConfig = Configuration::getInstance();

$t = new Template($globalConfig, 'expirychecker:expired');
$t->data['theme_color_scheme'] = $globalConfig->getOptionalString('theme.color-scheme', null);
$t->data['analytics_tracking_id'] = $globalConfig->getOptionalString('analytics.trackingId', '');
$t->data['form_target'] = Module::getModuleURL('expirychecker/expired.php');
$t->data['form_data'] = ['StateId' => $stateId];
$t->send();

Logger::info('expirychecker - User has been told that their password has expired.');
