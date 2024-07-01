<?php

use Sil\Psr3Adapters\Psr3StdOutLogger;
use Sil\SspUtils\AnnouncementUtils;
use SimpleSAML\Auth\Source;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Error\Error as SimpleSAMLError;
use SimpleSAML\Module\silauth\Auth\Source\auth\Authenticator;
use SimpleSAML\Module\silauth\Auth\Source\csrf\CsrfProtector;
use SimpleSAML\Module\silauth\Auth\Source\http\Request;
use SimpleSAML\Module\silauth\Auth\Source\SilAuth;
use SimpleSAML\Session;
use SimpleSAML\XHTML\Template;

/**
 * This page shows a username/password login form, and passes information from it
 * to the sspmod_silauth_Auth_Source_SilAuth class
 */

// Retrieve the authentication state
if (!array_key_exists('AuthState', $_REQUEST)) {
    throw new BadRequest('Missing AuthState parameter.');
}
$authStateId = $_REQUEST['AuthState'];
$state = State::loadState($authStateId, SilAuth::STAGEID);

$source = Source::getById($state[SilAuth::AUTHID]);
if ($source === null) {
    throw new Exception(
        'Could not find authentication source with id '
        . $state[SilAuth::AUTHID]
    );
}

$errorCode = null;
$errorParams = null;
$username = null;
$password = null;

$csrfProtector = new CsrfProtector(Session::getSessionFromRequest());

$globalConfig = Configuration::getInstance();
$authSourcesConfig = $globalConfig->getConfig('authsources.php');
$silAuthConfig = $authSourcesConfig->getConfigItem('silauth');

$recaptchaSiteKey = $silAuthConfig->getOptionalString('recaptcha.siteKey', null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $logger = new Psr3StdOutLogger();
        $csrfFromRequest = Request::sanitizeInputString(INPUT_POST, 'csrf-token');
        if ($csrfProtector->isTokenCorrect($csrfFromRequest)) {

            $username = Request::sanitizeInputString(INPUT_POST, 'username');
            $password = Request::getRawInputString(INPUT_POST, 'password');

            SilAuth::handleLogin(
                $authStateId,
                $username,
                $password
            );
        } else {
            $logger->error(json_encode([
                'event' => 'Failed CSRF',
                'username' => Request::sanitizeInputString(INPUT_POST, 'username'),
                'userAgent' => Request::getUserAgent(),
            ]));
        }

    } catch (SimpleSAMLError $e) {
        /* Login failed. Extract error code and parameters, to display the error. */
        $errorCode = $e->getErrorCode();
        $errorParams = $e->getParameters();
    }

    $csrfProtector->changeMasterToken();
}

$t = new Template($globalConfig, 'silauth:loginuserpass');
$t->data['stateparams'] = array('AuthState' => $authStateId);
$t->data['username'] = $username;
$t->data['forceUsername'] = false;
$t->data['rememberUsernameEnabled'] = false;
$t->data['rememberMeEnabled'] = false;
$t->data['errorcode'] = $errorCode;
$t->data['errorparams'] = $errorParams;
$t->data['csrfToken'] = $csrfProtector->getMasterToken();
$t->data['profileUrl'] = $state['templateData']['profileUrl'] ?? '';
$t->data['helpCenterUrl'] = $state['templateData']['helpCenterUrl'] ?? '';
$t->data['announcement'] = AnnouncementUtils::getAnnouncement();
$t->data['idpName'] = $globalConfig->getString('idp_display_name');
$t->data['passwordForgotUrl'] = $globalConfig->getOptionalString('passwordForgotUrl', '');

/* For simplicity's sake, don't bother telling this Request to trust any IP
 * addresses. This is okay because we only track the failures of untrusted
 * IP addresses, so there will be no failed logins of IP addresses we trust. */
$request = new Request();
if (Authenticator::isCaptchaRequired($username, $request->getUntrustedIpAddresses())) {
    $t->data['siteKey'] = $recaptchaSiteKey;
} else {
    $t->data['siteKey'] = null;
}

if (isset($state['SPMetadata'])) {
    $t->data['SPMetadata'] = $state['SPMetadata'];
} else {
    $t->data['SPMetadata'] = null;
}

$t->send();
exit();
