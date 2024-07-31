<?php

namespace SimpleSAML\Module\expirychecker;

class Utilities
{

    /**
     * Expects three strings for a url and what marks out the beginning
     * and end of the domain.
     *
     * Returns a string with the domain portion of the url (e.g. www.insitehome.org)
     */
    public static function getUrlDomain(string $in_url, string $start_marker = '//', string $end_marker = '/'): string
    {
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
    public static function haveSameDomain(
        string $url1,
        string $start_marker1,
        string $end_marker1,
        string $url2,
        string $start_marker2 = '//',
        string $end_marker2 = '/'
    ): int {
        $domain1 = self::getUrlDomain($url1, $start_marker1, $end_marker1);
        $domain2 = self::getUrlDomain($url2, $start_marker2, $end_marker2);

        if ($domain1 === $domain2) {
            return 1;
        }
        return 0;
    }

    /**
     * If the $relayState begins with "http", returns it.
     *   Otherwise, returns empty string.
     * @param string $relayState
     * @return string
     **/
    public static function getUrlFromRelayState(string $relayState): string
    {
        if (strpos($relayState, "http") === 0) {
            return $relayState;
        }

        return '';
    }
}


