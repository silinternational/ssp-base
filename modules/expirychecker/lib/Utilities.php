<?php

namespace SimpleSAML\Module\expirychecker;

class Utilities {

  /**
  * Expects three strings for a url and what marks out the beginning
  * and end of the domain. 
  * 
  * Returns a string with the domain portion of the url (e.g. www.insitehome.org)
  */
    public static function getUrlDomain($in_url, $start_marker='//', 
                                        $end_marker='/') {
    
        $sm_len = strlen($start_marker);
        $em_len = strlen($end_marker);
        $start_pos = strpos($in_url, $start_marker);
        $domain = substr($in_url, $start_pos + $sm_len);
      
        $end_pos = strpos($domain, $end_marker);
        $domain = substr($domain, 0, $end_pos);
        return $domain;
    }

        /**
         * Expects six strings for a url and what marks out the beginning
         * and end of its domain and then the same again for a second url.
         *
         * Returns 1 if the domains of the two urls are the same and 0 otherwise.
         */
    public static function haveSameDomain($url1, $start_marker1,
                                        $end_marker1, $url2, $start_marker2='//', 
                                        $end_marker2='/') {
        $domain1 = self::getUrlDomain($url1, $start_marker1, $end_marker1);
        $domain2 = self::getUrlDomain($url2, $start_marker2, $end_marker2);
                    
        if ($domain1 === $domain2) {
          return 1;
        }
        return 0;
    }

        /**
         * Expects four strings for ...
         *  - the url for changing the user's password,
  *  - the parameter label for the original url the user was headed to
  *  - the original url the user was headed to
  *  - the StateId parameter to add to the end of the new version of the url
  * Returns a string with special symbols urlencoded and then also encoded 
  *  for apex to use. If the domains of the change password url and the 
  *  original url are different, it appends the StateId to the output.
         */
    public static function convertOriginalUrl($passwordChangeUrl,
                                  $originalUrlParam, $originalUrl, $stateId ) {
        $sameDomain = self::haveSameDomain($passwordChangeUrl,
          '//', '/', $originalUrl, '//', '/');
        $original = $originalUrlParam . ":" . urlencode($originalUrl);
        // make changes that insite/apex needs in url
        $original = str_replace('%3A', '*COLON*', $original);
        $original = str_replace('%2C', '*COMMA*', $original);
        $original = str_replace('%26', '*AMPER*', $original);

        // if it already has a ?, then give it a &
        // otherwise give it a ? ...
        //  and then the StateId param
        if (!$sameDomain) {
            if (strpos($original, '%3F') !== false) {
                $original = $original . "*AMPER*" . $stateId;
            } else {
                $original = $original . '%3F' . $stateId;
            }
        }
        return $original;
    }
    
    /**
     * If the $relayState begins with "http", returns it.
     *   Otherwise, returns empty string.
     * @param string $relayState
     * @return string
     **/
    public static function getUrlFromRelayState($relayState) {
        if (strpos($relayState, "http") === 0) {
            return $relayState;
        }
        
        return '';
    }
}


