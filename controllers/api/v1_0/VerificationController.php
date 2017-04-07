<?php

namespace app\controllers\api\v1_0;

class VerificationController extends \app\components\RestController
{
    public $modelClass = 'app\models\Verification';

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
     * 请求验证
     * @Author   WindGreen<yqfwind@163.com>
     * @DateTime 2017-03-21T16:47:42+0800
     * @return   [type]                     [description]
     */
    public function actionCreate()
    {
        $model=new $this->modelClass;
        if($model->load(\yii::$app->getRequest()->getBodyParams(),'') && $model->request()){
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            //$response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['view', 'token' => $session->token], true));
            //return $model;
        }elseif (!$model->hasErrors()) {
            throw new \yii\web\ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

}
