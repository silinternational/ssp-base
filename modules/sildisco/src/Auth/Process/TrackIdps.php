<?php

namespace SimpleSAML\Module\sildisco\Auth\Process;

use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Session;

/**
 * Attribute filter for adding Idps to the session
 *
 */
class TrackIdps extends ProcessingFilter
{

    /**
     * Apply filter to save IDPs to session.
     *
     * @inheritDoc
     */
    public function process(array &$state): void
    {
        // get the authenticating Idp and add it to the list of previous ones
        $session = Session::getSessionFromRequest();
        $sessionDataType = "sildisco:authentication";
        $sessionKey = "authenticated_idps";

        $sessionValue = $session->getData($sessionDataType, $sessionKey);
        if (!$sessionValue) {
            $sessionValue = [];
        }

        // Will we need to wrap the idp in htmlspecialchars()
        $authIdps = $session->getAuthData("hub-discovery", "saml:AuthenticatingAuthority");

        if (!in_array($authIdps[0], $sessionValue)) {
            $sessionValue[$authIdps[0]] = $authIdps[0];
        }

        $session->setData($sessionDataType, $sessionKey, $sessionValue);
    }


}
