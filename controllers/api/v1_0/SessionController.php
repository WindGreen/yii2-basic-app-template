<?php

namespace app\controllers\api\v1_0;

use app\models\LoginForm;

class SessionController extends \app\components\RestController
{
    public $modelClass = 'app\models\Session';

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors=parent::behaviors();
        unset($behaviors['authenticator']);//不做登录验证
        //unset($behaviors['access']);
        $behaviors['access']['except']=['create'];//create操作不做访问控制
        return $behaviors;
    }

    /**
     * 登录
     * @Author   WindGreen<yqfwind@163.com>
     * @DateTime 2017-03-21T14:23:13+0800
     * @return   [type]                     [description]
     */
    public function actionCreate()
    {
        $model=new LoginForm;
        if($model->load(\yii::$app->getRequest()->getBodyParams(),'') && $model->login()){
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $session=\yii::$app->user->identity;
            $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['view', 'token' => $session->token], true));
            return $session;
        }elseif (!$model->hasErrors()) {
            throw new \yii\web\ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 注销
     * @Author   WindGreen<yqfwind@163.com>
     * @DateTime 2017-03-21T14:23:34+0800
     * @return   [type]                     [description]
     */
    public function actionDelete($token)
    {

    }

    public function actionUpdate($token)
    {

    }

    /**
     * 判断是否登录
     * @Author   WindGreen<yqfwind@163.com>
     * @DateTime 2017-03-21T14:24:16+0800
     * @param    [type]                     $token [description]
     * @return   [type]                            [description]
     */
    public function actionView($token)
    {

    }
}
