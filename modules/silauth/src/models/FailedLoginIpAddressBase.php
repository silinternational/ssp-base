<?php

namespace Sil\SilAuth\models;

use Yii;

/**
 * This is the model class for table "failed_login_ip_address".
 *
 * @property integer $id
 * @property string $ip_address
 * @property string $occurred_at_utc
 */
class FailedLoginIpAddressBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'failed_login_ip_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip_address', 'occurred_at_utc'], 'required'],
            [['occurred_at_utc'], 'safe'],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'occurred_at_utc' => Yii::t('app', 'Occurred At Utc'),
        ];
    }
}
