<?php

namespace SimpleSAML\Module\silauth\Auth\Source\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "failed_login_ip_address".
 *
 * @property integer $id
 * @property string $ip_address
 * @property string $occurred_at_utc
 */
class FailedLoginIpAddressBase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'failed_login_ip_address';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'occurred_at_utc' => Yii::t('app', 'Occurred At Utc'),
        ];
    }
}
