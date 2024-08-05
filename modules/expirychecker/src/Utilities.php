<?php

namespace SimpleSAML\Module\expirychecker;

class Utilities
{
    /**
     * If the $relayState begins with "http", returns it.
     *   Otherwise, returns empty string.
     * @param string $relayState
     * @return string
     **/
    public static function getUrlFromRelayState(string $relayState): string
    {
        if (str_starts_with($relayState, "http")) {
            return $relayState;
        }

        return '';
    }
}


