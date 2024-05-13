<?php
namespace Sil\SilAuth\time;

class UtcTime
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    
    private $utc;
    private $dateTime;
    
    /**
     * Create an object representing a date/time in Coordinated Universal Time
     * (UTC).
     * 
     * @param string $dateTimeString (Optional:) A string describing some
     *     date/time. If not given, 'now' will be used. For more information,
     *     see <http://php.net/manual/en/datetime.formats.php>.
     * @throws Exception If an invalid date/time string is provided, an
     *     \Exception will be thrown.
     */
    public function __construct(string $dateTimeString = 'now')
    {
        $this->utc = new \DateTimeZone('UTC');
        $this->dateTime = new \DateTime($dateTimeString, $this->utc);
    }
    
    public function __toString()
    {
        return $this->dateTime->format(self::DATE_TIME_FORMAT);
    }
    
    /**
     * Convert the given date/time description to a formatted date/time string
     * in the UTC time zone.
     * 
     * @param string $dateTimeString (Optional:) The date/time to use. If not
     *     given, 'now' will be used.
     * @return string
     * @throws Exception If an invalid date/time string is provided, an
     *     \Exception will be thrown.
     */
    public static function format(string $dateTimeString = 'now')
    {
        return (string)(new UtcTime($dateTimeString));
    }
    
    /**
     * Given a total number of seconds and an elapsed number of seconds, get the
     * remaining seconds until that total has passed. If the total has already
     * passed (i.e. if elapsed is equal to or greater than total), zero will be
     * returned.
     *
     * @param int $totalSeconds The total number of seconds.
     * @param int $elapsedSeconds The number of seconds that have already
     *     passed.
     * @return int The number of seconds remaining.
     */
    public static function getRemainingSeconds(int $totalSeconds, int $elapsedSeconds)
    {
        $remainingSeconds = $totalSeconds - $elapsedSeconds;
        return max($remainingSeconds, 0);
    }
    
    /**
     * Get the number of seconds we have to go back to get from this UTC time to
     * the given UTC time. A positive number will be returned if the given UTC
     * time occurred before this UTC time. If they are the same, zero will be
     * returned. Otherwise, a negative number will be returned.
     *
     * @param \Sil\SilAuth\time\UtcTime $otherUtcTime The other UTC time
     *     (presumably in the past, though not necessarily).
     * @return int The number of seconds
     */
    public function getSecondsSince(UtcTime $otherUtcTime)
    {
        return $this->getTimestamp() - $otherUtcTime->getTimestamp();
    }
    
    /**
     * Get the number of seconds since the given date/time string.
     *
     * @param string $dateTimeString A date/time string.
     * @return int The number of seconds that have elapsed since that date/time.
     * @throws Exception If an invalid date/time string is provided, an
     *     \Exception will be thrown.
     * @throws \InvalidArgumentException
     */
    public static function getSecondsSinceDateTime(string $dateTimeString)
    {
        if (empty($dateTimeString)) {
            throw new \InvalidArgumentException(sprintf(
                'The given value (%s) is not a date/time string.',
                var_export($dateTimeString, true)
            ));
        }
        $nowUtc = new UtcTime();
        $dateTimeUtc = new UtcTime($dateTimeString);
        return $nowUtc->getSecondsSince($dateTimeUtc);
    }
    
    public function getSecondsUntil(UtcTime $otherUtcTime)
    {
        return $otherUtcTime->getTimestamp() - $this->getTimestamp();
    }
    
    public function getTimestamp()
    {
        return $this->dateTime->getTimestamp();
    }
    
    /**
     * Get the current date/time (UTC) as a formatted string
     * 
     * @return string
     */
    public static function now()
    {
        return self::format('now');
    }
}
