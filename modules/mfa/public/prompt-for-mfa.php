<?php

/**
 * This "controller" (per MVC) must be called with the following query string
 * parameters:
 * - StateId
 * - mfaId
 */

use SimpleSAML\Module\mfa\LoggerFactory;
use SimpleSAML\Module\mfa\LoginBrowser;
use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\XHTML\Template;
use SimpleSAML\Module\mfa\Auth\Process\Mfa;

$stateId = filter_input(INPUT_GET, 'StateId');
if (empty($stateId)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$state = State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_PROMPT);
$mfaOptions = $state['mfaOptions'] ?? [];

$logger = LoggerFactory::getAccordingToState($state);

/*
 * Check for "Remember me for 30 days" cookies and if valid bypass mfa prompt
 */
$cookieHash = filter_input(INPUT_COOKIE, 'c1') ?? ''; // hashed string
$expireDate = filter_input(INPUT_COOKIE, 'c2') ?? 0;  // expiration timestamp
if (Mfa::isRememberMeCookieValid(base64_decode($cookieHash), $expireDate, $mfaOptions, $state)) {
    $logger->warning(json_encode([
        'event' => 'MFA skipped due to valid remember-me cookie',
        'employeeId' => $state['employeeId'],
    ]));

    unset($state['Attributes']['manager_email']);

    // This condition should never return
    ProcessingChain::resumeProcessing($state);
    throw new \Exception('Failed to resume processing auth proc chain.');
}

$mfaId = filter_input(INPUT_GET, 'mfaId');
$userAgent = LoginBrowser::getUserAgent();

if (empty($mfaId)) {
    $logger->critical(json_encode([
        'event' => 'MFA ID missing in URL. Choosing one and doing a redirect.',
        'employeeId' => $state['employeeId'],
    ]));
    
    // Pick an MFA ID and do a redirect to put that into the URL.
    $mfaOption = Mfa::getMfaOptionToUse($mfaOptions, $userAgent);
    $moduleUrl = SimpleSAML\Module::getModuleURL('mfa/prompt-for-mfa.php', [
        'mfaId' => $mfaOption['id'],
        'StateId' => $stateId,
    ]);
    $httpUtils = new HTTP;
    $httpUtils->redirectTrustedURL($moduleUrl);
    return;
}
$mfaOption = Mfa::getMfaOptionById($mfaOptions, $mfaId);

// If the user has submitted their MFA value...
if (filter_has_var(INPUT_POST, 'submitMfa')) {
    /* @var string|array $mfaSubmission */
    $mfaSubmission = filter_input(INPUT_POST, 'mfaSubmission');
    if (substr($mfaSubmission, 0, 1) == '{') {
        $mfaSubmission = json_decode($mfaSubmission, true);
    }

    $rememberMe = filter_input(INPUT_POST, 'rememberMe') ?? false;
    
    // NOTE: This will only return if validation fails.
    $errorMessage = Mfa::validateMfaSubmission(
        $mfaId,
        $state['employeeId'],
        $mfaSubmission,
        $state,
        $rememberMe,
        $logger,
        $mfaOption['type'],
        $state['rpOrigin']
    );
    
    $logger->warning(json_encode([
        'event' => 'MFA validation result: failed',
        'employeeId' => $state['employeeId'],
        'mfaType' => $mfaOption['type'],
        'error' => $errorMessage,
    ]));
}

$globalConfig = Configuration::getInstance();

$otherOptions = array_filter($mfaOptions, function($option) use ($mfaId) {
    return $option['id'] != $mfaId;
});
if (! empty($state['managerEmail'])) {
    $otherOptions[] = [
        'type' => 'manager',
        'callback' => '/module.php/mfa/send-manager-mfa.php?StateId='.htmlentities($stateId)
    ];
}
foreach ($otherOptions as &$option) {
    $option['callback'] = $option['callback'] ?? sprintf(
        '/module.php/mfa/prompt-for-mfa.php?StateId=%s&mfaId=%s',
        htmlentities($stateId),
        htmlentities($option['id'])
    );
    $option['image'] = 'mfa-' . $option['type'] . '.svg';
    $option['label'] = empty($option['id']) ? 'help' : $option['type'];
}

$mfaTemplateToUse = Mfa::getTemplateFor($mfaOption['type']);

$t = new Template($globalConfig, $mfaTemplateToUse);
$t->data['errorMessage'] = $errorMessage ?? null;
$t->data['mfaOption'] = $mfaOption;
$t->data['mfaOptions'] = $mfaOptions;
$t->data['stateId'] = $stateId;
$t->data['supportsWebAuthn'] = LoginBrowser::supportsWebAuthn($userAgent);
$browserJsHash = md5_file(__DIR__ . '/simplewebauthn/browser.js');
$t->data['browserJsPath'] = '/module.php/mfa/simplewebauthn/browser.js?v=' . $browserJsHash;
$t->data['managerEmail'] = $state['managerEmail'];
$t->data['otherOptions'] = $otherOptions;
$t->show();

$logger->info(json_encode([
    'event' => 'Prompted user for MFA',
    'employeeId' => $state['employeeId'],
    'mfaType' => $mfaOption['type'],
]));
