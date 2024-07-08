<?php

use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Module\profilereview\Auth\Process\ProfileReview;
use SimpleSAML\Module\profilereview\LoggerFactory;
use SimpleSAML\XHTML\Template;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$state = State::loadState($stateId, ProfileReview::STAGE_SENT_TO_NAG);
$logger = LoggerFactory::getAccordingToState($state);

// If the user has pressed the update button...
if (filter_has_var(INPUT_POST, 'update')) {
    ProfileReview::redirectToProfile($state);
    return;
} elseif (filter_has_var(INPUT_POST, 'continue')) {
    // The user has pressed the continue button.
    unset($state['Attributes']['mfa']);
    unset($state['Attributes']['method']);
    ProcessingChain::resumeProcessing($state);
    return;
}

$globalConfig = Configuration::getInstance();

$t = new Template($globalConfig, 'profilereview:' . $state['template']);
$t->data['profileUrl'] = $state['profileUrl'];
$t->data['methodOptions'] = $state['methodOptions'] ?? [];
$t->data['mfaOptions'] = $state['mfaOptions'] ?? [];
$t->data['mfaLearnMoreUrl'] = $state['mfaLearnMoreUrl'];
$t->send();

$logger->warning(json_encode([
    'module' => 'profilereview',
    'event' => 'presented nag',
    'template' => $state['template'],
    'employeeId' => $state['employeeId'],
]));
