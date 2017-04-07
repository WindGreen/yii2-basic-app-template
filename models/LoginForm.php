<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;
    private $_authenticaiton=false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $auth = $this->getAuthentication();

            if (!$auth || !$auth->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $auth = $this->getAuthentication();
            $userIdentity=UserIdentity::findOne($auth->toArray(['user_id','identity_type']));
            if(!$userIdentity) $userIdentity=new UserIdentity;
            $userIdentity->load($auth->toArray(['user_id','identifier','identity_type']),'');
            $userIdentity->token=UserIdentity::generateToken();
            $userIdentity->expire_at = $this->rememberMe ? 3600*24*30 : 0;
            $userIdentity->save();
            return Yii::$app->user->login($userIdentity, $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getAuthentication()
    {
        if ($this->_authenticaiton === false) {
            $this->_authenticaiton = Authentication::findOne(['identifier'=>$this->username,'identity_type'=>Authentication::TYPE_USERNAME]);
        }

        return $this->_authenticaiton;
    }
}
