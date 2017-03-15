<?php

namespace app\models;


class User extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    public static function findByUid($uid)
    {
        return static::findOne(['uid'=>$uid]);
    }

    public static function generateUniqueUid($length=32)
    {
        do $uid=\Yii::$app->security->generateRandomString();
        while(User::findByUid($uid));
        return $uid;
    }
}
