<?php

namespace SimpleSAML\Module\silauth\Auth\Source\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "failed_login_username".
 *
 * @property integer $id
 * @property string $username
 * @property string $occurred_at_utc
 */
class FailedLoginUsernameBase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'failed_login_username';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['username', 'occurred_at_utc'], 'required'],
            [['occurred_at_utc'], 'safe'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'occurred_at_utc' => Yii::t('app', 'Occurred At Utc'),
        ];
    }
}
