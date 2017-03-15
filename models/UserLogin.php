<?php

namespace app\models;

class UserLogin extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

   const IDENTITY_TYPE_USERNAME=11;

    public function behaviors()
    {
        return [     
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['identifier'=>$username,'identify_type'=>self::IDENTITY_TYPE_USERNAME]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->identifier;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password,$this->password);
    }


    public function setPassword($password)
    {
        $this->credential = \Yii::$app->security->generatePasswordHash($password);
    }

    public function getPassword()
    {
        return $this->credential;
    }


    public function getAuthKey()
    {
        throw new \Exception('不支持该方法');
        
    }


    public function validateAuthKey($authKey)
    {
        throw new \Exception('不支持该方法');
    }

    public static function signup($userInfo)
    {
        try{
            $user=new User;
            $user->username=$userInfo->username;
            $user->uid=User::generateUniqueUid();
            $user->save();

            $userLogin =new UserLogin;
            $userLogin->user_id       =$user->id;
            $userLogin->identify_type =UserLogin::IDENTITY_TYPE_USERNAME;
            $userLogin->identifier    =$userInfo->username;
            $userLogin->password      =$userInfo->password;
            $userLogin->expire_at     =0;

            $userLogin->save();
            return $userLogin;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
