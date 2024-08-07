<?php

namespace SimpleSAML\Module\silauth\Auth\Source\time;

use InvalidArgumentException;

/**
 * Class to enable assembling a human-friendly description of approximately how
 * long the user must wait before (at least) the given number of seconds have
 * elapsed.
 */
class WaitTime
{
    const UNIT_MINUTE = 'minute';
    const UNIT_SECOND = 'second';

    private int $friendlyNumber;
    private string $unit;

    /**
     * Constructor.
     *
     * NOTE: This will not be precise, as it may round up to have a more
     *       natural-sounding result (e.g. 20 seconds, rather than 17 seconds).
     *
     * @param int $secondsToWait The number of seconds the user must wait.
     */
    public function __construct(int $secondsToWait)
    {
        if ($secondsToWait <= 5) {
            $this->friendlyNumber = 5;
            $this->unit = self::UNIT_SECOND;
        } elseif ($secondsToWait <= 30) {
            $this->friendlyNumber = (int)ceil($secondsToWait / 10) * 10;
            $this->unit = self::UNIT_SECOND;
        } else {
            $this->friendlyNumber = (int)ceil($secondsToWait / 60);
            $this->unit = self::UNIT_MINUTE;
        }
    }

    public function getFriendlyNumber(): int
    {
        return $this->friendlyNumber;
    }

    /**
     * Get a WaitTime representing the longest of the given durations.
     *
     * @param int[] $durationsInSeconds A list of (at least one) duration(s), in
     *     seconds.
     * @return WaitTime
     */
    public static function getLongestWaitTime(array $durationsInSeconds): WaitTime
    {
        if (empty($durationsInSeconds)) {
            throw new InvalidArgumentException('No durations given.', 1487605801);
        }
        return new WaitTime(max($durationsInSeconds));
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function __toString()
    {
        return sprintf(
            '%s %s%s',
            $this->friendlyNumber,
            $this->unit,
            (($this->friendlyNumber === 1) ? '' : 's')
        );
    }
}
