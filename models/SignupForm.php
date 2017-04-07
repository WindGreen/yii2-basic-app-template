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
class SignupForm extends Model
{
    public $username;
    public $password;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 4, 'max' => 64],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) return false;

        $transaction = \Yii::$app->db->beginTransaction();
        try
        {        
            $user = new User;
            $user->username = $this->username;
            $user->uid=User::generateUid();
            $user->save();

            $authentication=new Authentication;
            $authentication->user_id=$user->id;
            $authentication->identity_type=Authentication::TYPE_USERNAME;
            $authentication->identifier=$this->username;
            $authentication->setPassword($this->password);
            $authentication->expire_at=0;
            $authentication->save();

            if($user->hasErrors() || $authentication->hasErrors()) {
                var_dump($user->getErrors());
                var_dump($authentication->getErrors());
                exit;
                //throw new \Exception("Error Processing Request", 1);
            }

            $transaction->commit();

            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(['username'=>$this->username]);
        }

        return $this->_user;
    }

}
