<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $type
 * @property string $nickname
 * @property string $phone
 * @property string $name
 * @property string $idcard
 * @property string $avatar
 * @property string $intro
 * @property string $email
 * @property string $address
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Authentication[] $authentications
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'phone'], 'required'],
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['uid'], 'string', 'max' => 32],
            [['nickname'], 'string', 'max' => 45],
            [['phone', 'name'], 'string', 'max' => 16],
            [['idcard'], 'string', 'max' => 18],
            [['avatar', 'intro', 'address'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['uid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'type' => 'Type',
            'nickname' => 'Nickname',
            'phone' => 'Phone',
            'name' => 'Name',
            'idcard' => 'Idcard',
            'avatar' => 'Avatar',
            'intro' => 'Intro',
            'email' => 'Email',
            'address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthentications()
    {
        return $this->hasMany(Authentication::className(), ['user_id' => 'id']);
    }
}
