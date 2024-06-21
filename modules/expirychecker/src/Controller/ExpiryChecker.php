<?php

declare(strict_types=1);

namespace SimpleSAML\Module\expirychecker\Controller;

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

/**
 * Controller class for the about2expire module.
 *
 * This class serves the different views available in the module.
 *
 * @package silinternational/ssp-base
 */
class ExpiryChecker
{
    /** @var \SimpleSAML\Configuration */
    protected Configuration $config;

    /**
     * Controller constructor.
     *
     * It initializes the global configuration for the controllers implemented here.
     *
     * @param \SimpleSAML\Configuration $config The configuration to use by the controllers.
     *
     * @throws \Exception
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function about2expire(): Template
    {
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
                if (!empty($returnTo)) {
                    $passwordChangeUrl .= '?returnTo=' . $returnTo;
                }
            }

            $httpUtils = new HTTP();
            $httpUtils->redirectTrustedURL($passwordChangeUrl, array());
        }

        $t = new Template($this->config, 'expirychecker:about2expire');
        $t->data['formTarget'] = Module::getModuleURL('expirychecker/about2expire');
        $t->data['formData'] = ['StateId' => $stateId];
        $t->data['daysLeft'] = $state['daysLeft'];
        $t->data['dayOrDays'] = (intval($state['daysLeft']) === 1 ? 'day' : 'days');
        $t->data['expiresAtTimestamp'] = $state['expiresAtTimestamp'];
        $t->data['accountName'] = $state['accountName'];

        Logger::info('expirychecker - User has been warned that their password will expire soon.');
        return $t;
    }

    public function expired(): Template
    {
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

        $t = new Template($this->config, 'expirychecker:expired');
        $t->data['formTarget'] = Module::getModuleURL('expirychecker/expired');
        $t->data['formData'] = ['StateId' => $stateId];
        $t->data['expiresAtTimestamp'] = $state['expiresAtTimestamp'];
        $t->data['accountName'] = $state['accountName'];

        Logger::info('expirychecker - User has been told that their password has expired.');
        return $t;
    }
}
