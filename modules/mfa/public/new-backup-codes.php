<?php

/**
 * This "controller" (per MVC) expects the state to contain, among other things,
 * the following:
 * - mfaSetupUrl
 * - newBackupCodes
 */

use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Module\mfa\Auth\Process\Mfa;
use SimpleSAML\Module\mfa\LoggerFactory;
use SimpleSAML\XHTML\Template;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$state = State::loadState($stateId, Mfa::STAGE_SENT_TO_NEW_BACKUP_CODES_PAGE);
$logger = LoggerFactory::getAccordingToState($state);

// If the user pressed the continue button...
if (filter_has_var(INPUT_POST, 'continue')) {
    unset($state['Attributes']['manager_email']);

    ProcessingChain::resumeProcessing($state);
    return;
}

$globalConfig = Configuration::getInstance();

$t = new Template($globalConfig, 'mfa:new-backup-codes');
$t->data['mfaSetupUrl'] = $state['mfaSetupUrl'];
$t->data['newBackupCodes'] = $state['newBackupCodes'] ?? [];
$t->data['idpName'] = $globalConfig->getString('idp_display_name');
$t->data['codesForDownload'] = urlencode(
    $t->data['idpName'] . "\r\n" . join("\r\n", $t->data['newBackupCodes'])
);
$t->data['codesForClipboard'] = $t->data['idpName'] . "\n" . join("\n", $t->data['newBackupCodes']);
$t->send();
