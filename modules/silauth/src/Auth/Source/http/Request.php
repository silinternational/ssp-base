<?php

namespace SimpleSAML\Module\silauth\Auth\Source\http;

use InvalidArgumentException;
use IP;
use IPBlock;
use SimpleSAML\Module\silauth\Auth\Source\text\Text;

class Request
{
    /**
     * The list of trusted IP addresses.
     *
     * @var IP[]
     */
    private array $trustedIpAddresses = [];

    /**
     * The list of trusted IP address ranges (aka. blocks).
     *
     * @var IPBlock[]
     */
    private array $trustedIpAddressRanges = [];

    /**
     * Constructor.
     *
     * @param string[] $ipAddressesToTrust The list of IP addresses (IPv4 and/or
     *     IPv6, specific IP's or CIDR ranges) to trust, and thus not to enforce
     *     any rate-limits on.
     */
    public function __construct(array $ipAddressesToTrust = [])
    {
        foreach ($ipAddressesToTrust as $ipAddress) {
            if ($this->isValidIpAddress($ipAddress)) {
                $this->trustIpAddress($ipAddress);
            } else {
                $this->trustIpAddressRange($ipAddress);
            }
        }
    }

    public function getCaptchaResponse(): string
    {
        return self::sanitizeInputString(INPUT_POST, 'g-recaptcha-response');
    }

    /**
     * Get the list of IP addresses from the current HTTP request. They will be
     * in order such that the last IP address in the list belongs to the device
     * that most recently handled the request (probably our load balancer). The
     * IP address first in the list is both (A) more likely to be the user's
     * actual IP address and (B) most likely to be forged.
     *
     * @return string[] A list of IP addresses.
     */
    public function getIpAddresses(): array
    {
        $ipAddresses = [];

        // First add the X-Forwarded-For IP addresses.
        $xForwardedFor = self::sanitizeInputString(
            INPUT_SERVER,
            'HTTP_X_FORWARDED_FOR'
        );
        foreach (explode(',', $xForwardedFor) as $xffIpAddress) {
            $trimmedIpAddress = trim($xffIpAddress);
            if (self::isValidIpAddress($trimmedIpAddress)) {
                $ipAddresses[] = $trimmedIpAddress;
            }
        }

        /* Finally, add the REMOTE_ADDR server value, since it belongs to the
         * device that directly passed this request to our application.  */
        $remoteAddr = self::sanitizeInputString(INPUT_SERVER, 'REMOTE_ADDR');
        if (self::isValidIpAddress($remoteAddr)) {
            $ipAddresses[] = $remoteAddr;
        }

        return $ipAddresses;
    }

    /**
     * Get the IP address that this request most likely originated from.
     *
     * @return string|null An IP address, or null if none was available.
     */
    public function getMostLikelyIpAddress(): ?string
    {
        $untrustedIpAddresses = $this->getUntrustedIpAddresses();

        /* Given the way X-Forwarded-For (and other?) headers work, the last
         * entry in the list was the IP address of the system closest to our
         * application. Once we filter out trusted IP addresses (such as that of
         * our load balancer, etc.), the last remaining IP address in the list
         * is probably the one we care about:
         *
         * "Since it is easy to forge an X-Forwarded-For field the given
         *  information should be used with care. The last IP address is always
         *  the IP address that connects to the last proxy, which means it is
         *  the most reliable source of information."
         * - https://en.wikipedia.org/wiki/X-Forwarded-For
         */
        $userIpAddress = end($untrustedIpAddresses);

        /* Make sure we actually ended up with an IP address (not FALSE, which
         * `last()` would return if there were no entries).  */
        return self::isValidIpAddress($userIpAddress) ? $userIpAddress : null;
    }

    /**
     * Retrieve input data (see `filter_input(...)` for details) as a string but
     * DO NOT sanitize it. If it is a string, it will be returned as is. If it
     * is not a string, an empty string will be returned, so that the return
     * type will always be a string.
     *
     * @param int $inputType Example: INPUT_POST
     * @param string $variableName Example: 'username'
     * @return string
     */
    public static function getRawInputString(int $inputType, string $variableName): string
    {
        $input = filter_input($inputType, $variableName);
        return is_string($input) ? $input : '';
    }

    public function getUntrustedIpAddresses(): array
    {
        $untrustedIpAddresses = [];
        foreach ($this->getIpAddresses() as $ipAddress) {
            if (!$this->isTrustedIpAddress($ipAddress)) {
                $untrustedIpAddresses[] = $ipAddress;
            }
        }
        return $untrustedIpAddresses;
    }

    /**
     * Get the User-Agent string.
     *
     * @return string The UA string, or an empty string if not found.
     */
    public static function getUserAgent(): string
    {
        return self::sanitizeInputString(INPUT_SERVER, 'HTTP_USER_AGENT');
    }

    /**
     * Determine whether the given IP address is trusted (either specifically or
     * because it is in a trusted range).
     *
     * @param string $ipAddress The IP address in question.
     * @return bool
     */
    public function isTrustedIpAddress(string $ipAddress): bool
    {
        foreach ($this->trustedIpAddresses as $trustedIp) {
            if ($trustedIp->numeric() === IP::create($ipAddress)->numeric()) {
                return true;
            }
        }

        foreach ($this->trustedIpAddressRanges as $trustedIpBlock) {
            if ($trustedIpBlock->containsIP($ipAddress)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check that a given string is a valid IP address (IPv4 or IPv6).
     *
     * @param string $ipAddress The IP address in question.
     * @return bool
     */
    public static function isValidIpAddress(string $ipAddress): bool
    {
        $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;
        return (filter_var($ipAddress, FILTER_VALIDATE_IP, $flags) !== false);
    }

    /**
     * Retrieve input data (see `filter_input(...)` for details) and sanitize
     * it (see Text::sanitizeString).
     *
     * @param int $inputType Example: INPUT_POST
     * @param string $variableName Example: 'username'
     * @return string
     */
    public static function sanitizeInputString(int $inputType, string $variableName): string
    {
        return Text::sanitizeString(filter_input($inputType, $variableName));
    }

    public function trustIpAddress(string $ipAddress): void
    {
        if (!self::isValidIpAddress($ipAddress)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not a valid IP address.',
                var_export($ipAddress, true)
            ));
        }
        $this->trustedIpAddresses[] = IP::create($ipAddress);
    }

    public function trustIpAddressRange(string $ipAddressRangeString): void
    {
        $ipBlock = IPBlock::create($ipAddressRangeString);
        $this->trustedIpAddressRanges[] = $ipBlock;
    }
}
