<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authentication".
 *
 * @property integer $user_id
 * @property integer $identity_type
 * @property string $identifier
 * @property string $credential
 * @property integer $expire_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Session $session
 */
class Authentication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authentication';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'identity_type', 'identifier', 'credential'], 'required'],
            [['user_id', 'identity_type', 'expire_at', 'created_at', 'updated_at'], 'integer'],
            [['identifier'], 'string', 'max' => 64],
            [['credential'], 'string', 'max' => 128],
            [['identity_type', 'identifier'], 'unique', 'targetAttribute' => ['identity_type', 'identifier'], 'message' => 'The combination of Identity Type and Identifier has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'credential' => 'Credential',
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
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(Session::className(), ['user_id' => 'user_id', 'identity_type' => 'identity_type']);
    }
}
