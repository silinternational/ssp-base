<?php

namespace SimpleSAML\Module\expirychecker\Auth\Process;

use Psr\Log\LoggerInterface;
use Sil\Psr3Adapters\Psr3SamlLogger;
use Sil\SspExpiryChecker\Validator;
use SimpleSAML\Auth\ProcessingChain;
use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Auth\State;
use SimpleSAML\Module;
use SimpleSAML\Module\expirychecker\Utilities;
use SimpleSAML\Session;
use SimpleSAML\Utils\HTTP;

/**
 * Filter which either warns the user that their password is "about to expire"
 * (giving them the option of changing it now or later) or tells them that it
 * has expired (only allowing them to go change their password).
 *
 * See README.md for sample (and explanation of) expected configuration.
 */
class ExpiryDate extends ProcessingFilter
{
    const HAS_SEEN_SPLASH_PAGE = 'has_seen_splash_page';
    const SESSION_TYPE = 'expirychecker';
    
    private $warnDaysBefore = 14;
    private $originalUrlParam = 'originalurl';
    private $passwordChangeUrl = null;
    private $accountNameAttr = null;
    private $employeeIdAttr = 'employeeNumber';
    private $expiryDateAttr = null;
    private $dateFormat = 'Y-m-d';
    
    /** @var LoggerInterface */
    protected $logger;
    
    /**
     * Initialize this filter.
     *
     * @param array $config  Configuration information about this filter.
     * @param mixed $reserved  For future use.
     */
    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);
        
        $this->initComposerAutoloader();
        
        assert('is_array($config)');
        
        $this->initLogger($config);
        
        $this->loadValuesFromConfig($config, [
            'warnDaysBefore' => [
                Validator::INT,
            ],
            'originalUrlParam' => [
                Validator::STRING,
                Validator::NOT_EMPTY,
            ],
            'passwordChangeUrl' => [
                Validator::STRING,
                Validator::NOT_EMPTY,
            ],
            'accountNameAttr' => [
                Validator::STRING,
                Validator::NOT_EMPTY,
            ],
            'expiryDateAttr' => [
                Validator::STRING,
                Validator::NOT_EMPTY,
            ],
            'dateFormat' => [
                Validator::STRING,
                Validator::NOT_EMPTY,
            ],
        ]);
    }
    
    protected function loadValuesFromConfig($config, $attributeRules)
    {
        foreach ($attributeRules as $attribute => $rules) {
            if (array_key_exists($attribute, $config)) {
                $this->$attribute = $config[$attribute];
            }
            
            Validator::validate($this->$attribute, $rules, $this->logger, $attribute);
        }
    }
    
    /**
     * Get the specified attribute from the given state data.
     *
     * NOTE: If the attribute's data is an array, the first value will be
     *       returned. Otherwise, the attribute's data will simply be returned
     *       as-is.
     *
     * @param string $attributeName The name of the attribute.
     * @param array $state The state data.
     * @return mixed The attribute value, or null if not found.
     */
    protected function getAttribute($attributeName, $state)
    {
        $attributeData = $state['Attributes'][$attributeName] ?? null;
        
        if (is_array($attributeData)) {
            return $attributeData[0] ?? null;
        }
        
        return $attributeData;
    }
    
    /**
     * Calculate how many days remain between now and when the password will
     * expire.
     *
     * @param int $expiryTimestamp The timestamp for when the password will
     *     expire.
     * @return int The number of days remaining
     */
    protected function getDaysLeftBeforeExpiry($expiryTimestamp)
    {
        $now = time();
        $end = $expiryTimestamp;
        return round(($end - $now) / (24*60*60));
    }
    
    /**
     * Get the timestamp for when the user's password will expire, throwing an
     * exception if unable to do so.
     *
     * @param string $expiryDateAttr The name of the attribute where the
     *     expiration date (as a string) is stored.
     * @param array $state The state data.
     * @return int The expiration timestamp.
     * @throws \Exception
     */
    protected function getExpiryTimestamp($expiryDateAttr, $state)
    {
        $expiryDateString = $this->getAttribute($expiryDateAttr, $state);
        
        // Ensure that EVERY user login provides a usable password expiration date.
        $expiryTimestamp = strtotime($expiryDateString) ?: null;
        if (empty($expiryTimestamp)) {
            throw new \Exception(sprintf(
                "We could not understand the expiration date (%s, from %s) for "
                . "the user's password, so we do not know whether their "
                . "password is still valid.",
                var_export($expiryDateString, true),
                var_export($expiryDateAttr, true)
            ), 1496843359);
        }
        return $expiryTimestamp;
    }
    
    public static function hasSeenSplashPageRecently()
    {
        $session = Session::getSessionFromRequest();
        return (bool)$session->getData(
            self::SESSION_TYPE,
            self::HAS_SEEN_SPLASH_PAGE
        );
    }
    
    public static function skipSplashPagesFor($seconds)
    {
        $session = Session::getSessionFromRequest();
        $session->setData(
            self::SESSION_TYPE,
            self::HAS_SEEN_SPLASH_PAGE,
            true,
            $seconds
        );
        $session->save();
    }
    
    protected function initComposerAutoloader()
    {
        $path = __DIR__ . '/../../../vendor/autoload.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
    
    protected function initLogger($config)
    {
        $loggerClass = $config['loggerClass'] ?? Psr3SamlLogger::class;
        $this->logger = new $loggerClass();
        if (! $this->logger instanceof LoggerInterface) {
            throw new \Exception(sprintf(
                'The specified loggerClass (%s) does not implement '
                . '\\Psr\\Log\\LoggerInterface.',
                var_export($loggerClass, true)
            ), 1496928725);
        }
    }
    
    /**
     * See if the given timestamp is in the past.
     *
     * @param int $timestamp The timestamp to check.
     * @return bool
     */
    public function isDateInPast(int $timestamp)
    {
        return ($timestamp < time());
    }
    
    /**
     * Check whether the user's password has expired.
     *
     * @param int $expiryTimestamp The timestamp for when the user's password
     *     will expire.
     * @return bool
     */
    public function isExpired(int $expiryTimestamp)
    {
        return $this->isDateInPast($expiryTimestamp);
    }
    
    /**
     * Check whether it's time to warn the user that they will need to change
     * their password soon.
     *
     * @param int $expiryTimestamp The timestamp for when the password expires.
     * @param int $warnDaysBefore How many days before the expiration we should
     *     warn the user.
     * @return boolean
     */
    public function isTimeToWarn($expiryTimestamp, $warnDaysBefore)
    {
        $daysLeft = $this->getDaysLeftBeforeExpiry($expiryTimestamp);
        return ($daysLeft <= $warnDaysBefore);
    }

    /**
     * Redirect the user to the change password url if they haven't gone
     *   there in the last 10 minutes
     * @param array $state
     * @param string $accountName
     * @param string $passwordChangeUrl
     * @param string $change_pwd_session
     * @param int $expiryTimestamp The timestamp when the password will expire.
     */
    public function redirect2PasswordChange(
        &$state,
        $accountName,
        $passwordChangeUrl,
        $change_pwd_session,
        $expiryTimestamp
    ) {
        $sessionType = 'expirychecker';
        /* Save state and redirect. */
        $state['expiresAtTimestamp'] = $expiryTimestamp;
        $state['accountName'] = $accountName;
        $id = State::saveState(
            $state,
            'expirychecker:redirected_to_password_change_url'
        );
        $ignoreMinutes = 60;

        $session = Session::getSessionFromRequest();
        $idpExpirySession = $session->getData($sessionType, $change_pwd_session);
        
        // If the session shows that the User already passed this way,
        //  don't redirect to change password page
        if ($idpExpirySession !== null) {
            ProcessingChain::resumeProcessing($state);
        } else {
            // Otherwise, set a value to tell us they've probably changed
            // their password, in order to allow password to get propagated
            $session->setData(
                $sessionType,
                $change_pwd_session,
                1,
                (60 * $ignoreMinutes)
            );
            $session->save();
        }
        
        
        /* If state already has the change password url, go straight there to
         * avoid eternal loop between that and the idp. Otherwise add the
         * original destination url as a parameter.  */
        if (array_key_exists('saml:RelayState', $state)) {
            $relayState = $state['saml:RelayState'];
            if (strpos($relayState, $passwordChangeUrl) !== false) {
                ProcessingChain::resumeProcessing($state);
            } else {
                $returnTo = Utilities::getUrlFromRelayState(
                    $relayState
                );
                if (! empty($returnTo)) {
                    $passwordChangeUrl .= '?returnTo=' . $returnTo;
                }
            }
        }

        $this->logger->warning(json_encode([
            'event' => 'expirychecker: redirecting to change password',
            'accountName' => $accountName,
            'passwordChangeUrl' => $passwordChangeUrl,
        ]));

        HTTP::redirectTrustedURL($passwordChangeUrl, array());
    }
    
    /**
     * Apply this AuthProc Filter.
     *
     * @param array &$state The current state.
     */
    public function process(&$state)
    {
        $employeeId = $this->getAttribute($this->employeeIdAttr, $state);
        
        /* If the user has already seen a splash page from this AuthProc
         * recently, simply let them pass on through (so they can get into the
         * change-password website, for example).  */
        if (self::hasSeenSplashPageRecently()) {
            $this->logger->warning(json_encode([
                'event' => 'expirychecker: skip message, seen recently',
                'employeeId' => $employeeId,
            ]));
            return;
        }
        
        // Get the necessary info from the state data.
        $accountName = $this->getAttribute($this->accountNameAttr, $state);
        $expiryTimestamp = $this->getExpiryTimestamp($this->expiryDateAttr, $state);
        
        $this->logger->warning(json_encode([
            'event' => 'expirychecker: will check expiration date',
            'employeeId' => $employeeId,
            'accountName' => $accountName,
            'expiryDateAttrValue' => $this->getAttribute($this->expiryDateAttr, $state),
            'expiryTimestamp' => $expiryTimestamp,
        ]));
        
        if ($this->isExpired($expiryTimestamp)) {
            $this->redirectToExpiredPage($state, $accountName, $expiryTimestamp);
        }
        
        // Display a password expiration warning page if it's time to do so.
        if ($this->isTimeToWarn($expiryTimestamp, $this->warnDaysBefore)) {
            $this->redirectToWarningPage($state, $accountName, $expiryTimestamp);
        }
        
        $this->logger->warning(json_encode([
            'event' => 'expirychecker: no action necessary',
            'employeeId' => $employeeId,
        ]));
    }
    
    /**
     * Redirect the user to the expired-password page.
     *
     * @param array $state The state data.
     * @param string $accountName The name of the user account.
     * @param int $expiryTimestamp When the password expired.
     */
    public function redirectToExpiredPage(&$state, $accountName, $expiryTimestamp)
    {
        assert('is_array($state)');
        
        $this->logger->warning(json_encode([
            'event' => 'expirychecker: password expired',
            'accountName' => $accountName,
        ]));

        /* Save state and redirect. */
        $state['expiresAtTimestamp'] = $expiryTimestamp;
        $state['accountName'] = $accountName;
        $state['passwordChangeUrl'] = $this->passwordChangeUrl;
        $state['originalUrlParam'] = $this->originalUrlParam;
        
        $id = State::saveState($state, 'expirychecker:expired');
        $url = Module::getModuleURL('expirychecker/expired.php');

        HTTP::redirectTrustedURL($url, array('StateId' => $id));
    }
    
    /**
     * Redirect the user to the warning page.
     *
     * @param array $state The state data.
     * @param string $accountName The name of the user account.
     * @param int $expiryTimestamp When the password will expire.
     */
    protected function redirectToWarningPage(&$state, $accountName, $expiryTimestamp)
    {
        assert('is_array($state)');
        
        $this->logger->warning(json_encode([
            'event' => 'expirychecker: about to expire',
            'accountName' => $accountName,
        ]));
        
        $daysLeft = $this->getDaysLeftBeforeExpiry($expiryTimestamp);
        $state['daysLeft'] = $daysLeft;
        
        if (isset($state['isPassive']) && $state['isPassive'] === true) {
            /* We have a passive request. Skip the warning. */
            return;
        }
        
        /* Save state and redirect. */
        $state['expiresAtTimestamp'] = $expiryTimestamp;
        $state['accountName'] = $accountName;
        $state['passwordChangeUrl'] = $this->passwordChangeUrl;
        $state['originalUrlParam'] = $this->originalUrlParam;
        
        $id = State::saveState($state, 'expirychecker:about2expire');
        $url = Module::getModuleURL('expirychecker/about2expire.php');

        HTTP::redirectTrustedURL($url, array('StateId' => $id));
    }
}
