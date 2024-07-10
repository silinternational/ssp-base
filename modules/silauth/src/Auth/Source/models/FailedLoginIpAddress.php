<?php

namespace SimpleSAML\Module\silauth\Auth\Source\models;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use SimpleSAML\Module\silauth\Auth\Source\auth\Authenticator;
use SimpleSAML\Module\silauth\Auth\Source\behaviors\CreatedAtUtcBehavior;
use SimpleSAML\Module\silauth\Auth\Source\http\Request;
use SimpleSAML\Module\silauth\Auth\Source\time\UtcTime;
use SimpleSAML\Module\silauth\Auth\Source\traits\LoggerAwareTrait;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class FailedLoginIpAddress extends FailedLoginIpAddressBase implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'ip_address' => Yii::t('app', 'IP Address'),
            'occurred_at_utc' => Yii::t('app', 'Occurred At (UTC)'),
        ]);
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => CreatedAtUtcBehavior::className(),
                'attributes' => [
                    Model::EVENT_BEFORE_VALIDATE => 'occurred_at_utc',
                ],
            ],
        ];
    }

    public static function countRecentFailedLoginsFor(string $ipAddress): int
    {
        $count = self::find()->where([
            'ip_address' => strtolower($ipAddress),
        ])->andWhere([
            '>=', 'occurred_at_utc', UtcTime::format('-60 minutes')
        ])->count();
        if (!is_numeric($count)) {
            throw new \Exception('expected a numeric value for recent failed logins by IP address, got ' . $count);
        }
        return (int)$count;
    }

    public static function getFailedLoginsFor(string $ipAddress): array
    {
        if (!Request::isValidIpAddress($ipAddress)) {
            throw new \InvalidArgumentException(sprintf(
                '%s is not a valid IP address.',
                var_export($ipAddress, true)
            ));
        }

        return self::findAll(['ip_address' => strtolower($ipAddress)]);
    }

    /**
     * Get the most recent failed-login record for the given IP address, or null
     * if none is found.
     *
     * @param string $ipAddress The IP address.
     * @return FailedLoginIpAddress|null
     */
    public static function getMostRecentFailedLoginFor(string $ipAddress): ?FailedLoginIpAddress
    {
        return self::find()->where([
            'ip_address' => strtolower($ipAddress),
        ])->orderBy([
            'occurred_at_utc' => SORT_DESC,
        ])->one();
    }

    /**
     * Get the number of seconds remaining until the specified IP address is
     * no longer blocked by a rate-limit. Returns zero if it is not currently
     * blocked.
     *
     * @param string $ipAddress The IP address in question
     * @return int The number of seconds
     */
    public static function getSecondsUntilUnblocked(string $ipAddress): int
    {
        $failedLogin = self::getMostRecentFailedLoginFor($ipAddress);

        return Authenticator::getSecondsUntilUnblocked(
            self::countRecentFailedLoginsFor($ipAddress),
            $failedLogin->occurred_at_utc ?? null
        );
    }

    public function init(): void
    {
        $this->initializeLogger();
        parent::init();
    }

    public static function isCaptchaRequiredFor(string $ipAddress): bool
    {
        return Authenticator::isEnoughFailedLoginsToRequireCaptcha(
            self::countRecentFailedLoginsFor($ipAddress)
        );
    }

    public static function isCaptchaRequiredForAnyOfThese(array $ipAddresses): bool
    {
        foreach ($ipAddresses as $ipAddress) {
            if (self::isCaptchaRequiredFor($ipAddress)) {
                return true;
            }
        }
        return false;
    }

    public static function isRateLimitBlocking(string $ipAddress): bool
    {
        $secondsUntilUnblocked = self::getSecondsUntilUnblocked($ipAddress);
        return ($secondsUntilUnblocked > 0);
    }

    public static function isRateLimitBlockingAnyOfThese(array $ipAddresses): bool
    {
        foreach ($ipAddresses as $ipAddress) {
            if (self::isRateLimitBlocking($ipAddress)) {
                return true;
            }
        }
        return false;
    }

    public static function recordFailedLoginBy(
        array           $ipAddresses,
        LoggerInterface $logger
    ): void {
        foreach ($ipAddresses as $ipAddress) {
            $newRecord = new FailedLoginIpAddress(['ip_address' => strtolower($ipAddress)]);

            if (!$newRecord->save()) {
                $logger->critical(json_encode([
                    'event' => 'Failed to update login attempts counter in '
                        . 'database, so unable to rate limit that IP address.',
                    'ipAddress' => $ipAddress,
                    'errors' => $newRecord->getErrors(),
                ]));
            }
        }
    }

    public static function resetFailedLoginsBy(array $ipAddresses): void
    {
        foreach ($ipAddresses as $ipAddress) {
            self::deleteAll(['ip_address' => strtolower($ipAddress)]);
        }
    }
}
