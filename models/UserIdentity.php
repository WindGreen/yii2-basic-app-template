<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user_session".
 *
 * @property integer $user_id
 * @property integer $identity_type
 * @property string $identifier
 * @property string $token
 * @property integer $expire_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property UserAuthentication $user
 */
class UserIdentity extends Session implements IdentityInterface
{


    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id'=>$id]);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->user ? $this->user->id : null;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    public function getUsername()
    {
        return $this->user ? $this->user->username : null;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function validateUidAndToken($uid,$token)
    {
        $session=static::findByToken($token);
        if( $session && $session->user && $uid===$session->user->uid )
            return $session;
        else
            return null;
            
    }
}
