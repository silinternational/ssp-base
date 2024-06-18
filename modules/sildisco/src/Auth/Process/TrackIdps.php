<?php

namespace SimpleSAML\Module\sildisco\Auth\Process;

/**
 * Attribute filter for adding Idps to the session
 *
 */
class TrackIdps extends \SimpleSAML\Auth\ProcessingFilter {

    /**
     * Apply filter to save IDPs to session.
     *
     * @param array &$request  The current request
     */
    public function process(&$request) {
        // get the authenticating Idp and add it to the list of previous ones
        $session = \SimpleSAML\Session::getSessionFromRequest();
        $sessionDataType = "sildisco:authentication";
        $sessionKey = "authenticated_idps";
    
        $sessionValue = $session->getData($sessionDataType, $sessionKey);
        if ( ! $sessionValue) {
            $sessionValue = [];
        }

        // Will we need to wrap the idp in htmlspecialchars()
        $authIdps = $session->getAuthData("hub-discovery", "saml:AuthenticatingAuthority");

        if ( ! in_array($authIdps[0], $sessionValue)) {
            $sessionValue[$authIdps[0]] = $authIdps[0];
        }
    
        $session->setData($sessionDataType, $sessionKey, $sessionValue); 
    }        
        

}
