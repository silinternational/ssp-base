<?php
namespace Sil\SilAuth\models;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sil\SilAuth\auth\Authenticator;
use Sil\SilAuth\behaviors\CreatedAtUtcBehavior;
use Sil\SilAuth\time\UtcTime;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

class FailedLoginUsername extends FailedLoginUsernameBase implements LoggerAwareInterface
{
    use \Sil\SilAuth\traits\LoggerAwareTrait;
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'occurred_at_utc' => Yii::t('app', 'Occurred At (UTC)'),
        ]);
    }
    
    public function behaviors()
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
    
    public static function countRecentFailedLoginsFor($username)
    {
        return self::find()->where([
            'username' => strtolower($username),
        ])->andWhere([
            '>=', 'occurred_at_utc', UtcTime::format('-60 minutes')
        ])->count();
    }
    
    /**
     * Find the records with the given username (if any).
     * 
     * @param string $username The username.
     * @return FailedLoginUsername[] An array of any matching records.
     */
    public static function getFailedLoginsFor($username)
    {
        return self::findAll(['username' => strtolower($username)]);
    }
    
    /**
     * Get the most recent failed-login record for the given username, or null
     * if none is found.
     *
     * @param string $username The username.
     * @return FailedLoginUsername|null
     */
    public static function getMostRecentFailedLoginFor($username)
    {
        return self::find()->where([
            'username' => strtolower($username),
        ])->orderBy([
            'occurred_at_utc' => SORT_DESC,
        ])->one();
    }
    
    /**
     * Get the number of seconds remaining until the specified username is
     * no longer blocked by a rate-limit. Returns zero if the user is not
     * currently blocked.
     * 
     * @param string $username The username in question
     * @return int The number of seconds
     */
    public static function getSecondsUntilUnblocked($username)
    {
        $failedLogin = self::getMostRecentFailedLoginFor($username);
        
        return Authenticator::getSecondsUntilUnblocked(
            self::countRecentFailedLoginsFor($username),
            $failedLogin->occurred_at_utc ?? null
        );
    }
    
    public function init()
    {
        $this->initializeLogger();
        parent::init();
    }
    
    /**
     * Find out whether a rate limit is blocking the specified username.
     *
     * @param string $username The username
     * @return bool
     */
    public static function isRateLimitBlocking($username)
    {
        $secondsUntilUnblocked = self::getSecondsUntilUnblocked($username);
        return ($secondsUntilUnblocked > 0);
    }
    
    public static function isCaptchaRequiredFor($username)
    {
        if (empty($username)) {
            return false;
        }
        return Authenticator::isEnoughFailedLoginsToRequireCaptcha(
            self::countRecentFailedLoginsFor($username)
        );
    }
    
    public static function recordFailedLoginBy(
        $username,
        LoggerInterface $logger
    ) {
        $newRecord = new FailedLoginUsername(['username' => strtolower($username)]);
        if ( ! $newRecord->save()) {
            $logger->critical(json_encode([
                'event' => 'Failed to update login attempts counter in '
                . 'database, so unable to rate limit that username.',
                'username' => $username,
                'errors' => $newRecord->getErrors(),
            ]));
        }
    }
    
    public static function resetFailedLoginsBy($username)
    {
        self::deleteAll(['username' => strtolower($username)]);
    }
}
