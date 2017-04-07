<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property integer $user_id
 * @property integer $identity_type
 * @property string $identifier
 * @property string $token
 * @property integer $expire_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Authentication $user
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'identity_type', 'identifier', 'token'], 'required'],
            [['user_id', 'identity_type', 'expire_at', 'created_at', 'updated_at'], 'integer'],
            [['identifier'], 'string', 'max' => 64],
            [['token'], 'string', 'max' => 128],
            [['token'], 'unique'],
            [['user_id', 'identity_type'], 'exist', 'skipOnError' => true, 'targetClass' => Authentication::className(), 'targetAttribute' => ['user_id' => 'user_id', 'identity_type' => 'identity_type']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'identity_type' => 'Identity Type',
            'identifier' => 'Identifier',
            'token' => 'Token',
            'expire_at' => 'Expire At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Authentication::className(), ['user_id' => 'user_id', 'identity_type' => 'identity_type']);
    }
}
