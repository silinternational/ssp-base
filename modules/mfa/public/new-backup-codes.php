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
$t->data['mfa_setup_url'] = $state['mfaSetupUrl'];
$t->data['new_backup_codes'] = $state['newBackupCodes'] ?? [];
$t->data['idp_name'] = $globalConfig->getString('idp_display_name');
$t->data['codes_for_download'] = urlencode(
    $t->data['idp_name'] . "\r\n" . join("\r\n", $t->data['new_backup_codes'])
);
$t->data['codes_for_clipboard'] = $t->data['idp_name'] . "\n" . join("\n", $t->data['new_backup_codes']);
$t->send();
