<?php
namespace Sil\SilAuth\text;

class Text
{
    /**
     * Sanitize a given string by trimming it (see `trim()`) and stripping low
     * ASCII characters (< 32) and backticks (`).
     *
     * @param string|mixed $input The input.
     * @return string The sanitized string.
     */
    public static function sanitizeString($input)
    {
        $inputAsString = is_string($input) ? $input : '';
        $output = filter_var($inputAsString, FILTER_SANITIZE_STRING, [
            'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK,
        ]);
        return trim($output);
    }
    
    /**
     * See if the given string (haystack) starts with the given prefix (needle).
     * 
     * @param string $haystack The string to search.
     * @param string $needle The string to search for.
     * @return boolean
     */
    public static function startsWith(string $haystack, string $needle)
    {
        $length = mb_strlen($needle);
        return (mb_substr($haystack, 0, $length) === $needle);
    }
}
