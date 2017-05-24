<?php
namespace app\components;

use yii\filters\auth\AuthMethod;

class SignatureAuth extends AuthMethod
{
    public $realm = 'api';

    public $auth;

    /**
     * Authenticates the current user.
     * @param User $user
     * @param Request $request
     * @param Response $response
     * @return IdentityInterface the authenticated user identity. If authentication information is not provided, null will be returned.
     * @throws UnauthorizedHttpException if authentication information is provided but is invalid.
     */
    public function authenticate($user, $request, $response)
    {
        $headers=$request->headers;
        $params=$request->get();
        $params['app_key']   =$headers['X-APP-KEY'];
        $params['timestamp'] =$headers['X-APP-TIMESTAMP'];
        $params['signature'] =$headers['X-APP-SIGNATURE'];

        if(!isset($params['signature'],$params['app_key'],$params['timestamp']))
            return null;

        //时间戳有效判断
        if(false && abs(time()-intval($params['timestamp']))>60)
            $this->handleFailure($response);

        //根据app_key找app_secret
        if ($this->auth) {
            $identity = call_user_func($this->auth, $params['app_key']);
            if (!$identity) {
                $this->handleFailure($response);
            }
        } 
        else return null;//$identity=$user->$identityClass::findIdentityByIdentifier($params['app_key']);
        $params['app_secret']=$identity->token;

        $signature =$params['signature'];
        unset($params['signature']);
        asort($params,SORT_STRING);
        $str=http_build_query($params);

        if($signature===sha1($str))
        {
            $user->switchIdentity($identity);
            return $identity;
        }

        return null;
    }

    /**
     * Generates challenges upon authentication failure.
     * For example, some appropriate HTTP headers may be generated.
     * @param Response $response
     */
    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "Sign realm=\"{$this->realm}\"");
    }

}