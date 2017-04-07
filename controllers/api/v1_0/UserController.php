<?php

namespace app\controllers\api\v1_0;

use \yii;
use \yii\db\Exception;
use \app\models\SignupForm;
use \app\models\User;

class UserController extends \app\components\RestController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors=parent::behaviors();
        $behaviors['authenticator']['optional']=['create'];
        $behaviors['access']['except']=['create'];//create操作不做访问控制
        return $behaviors;
    }

    public function actionCreate()
    {
        $data  = \yii::$app->getRequest()->getBodyParams();
        $model = new SignupForm;
        if($model->load($data,'') && $model->signup()){
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $user = $model->getUser();
            $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['view', 'id' => $user->id], true));
            return $user;
        }elseif (!$model->hasErrors()) {
            throw new \yii\web\ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    public function actionUpdate($uid)
    {
        $data  = \yii::$app->getRequest()->getBodyParams();
        $model = User::findByUid($uid);
        $model->scenario=User::SCENARIO_UPDATE;
        if($model->load($data,'') && $model->save()){
            return $model;
        }elseif (!$model->hasErrors()) {
            throw new \yii\web\ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    public function actionView($uid)
    {
        $user=User::findByUid($uid);
        $this->checkAccess('view',$user);
        return $user;
    }

    public function actionInvestments($uid)
    {
        $model=User::findByUid($uid);//\yii::$app->user->identity;
        return $model && !$model->hasErrors() ? $model->userInvestProjects : $model;
    }

    public function actionPlatformComments($uid=null)
    {
        if($uid==null) $model=\yii::$app->user->identity->user;
        else $model=User::findByUid($uid);
        return $model && !$model->hasErrors() ? $model->platformComment : $model;
    }

    public function actionWithdrawals($uid)
    {
        $model=User::findByUid($uid);//\yii::$app->user->identity;
        return $model && !$model->hasErrors() ? $model->userWithdrawInvests : $model;
    }    
}
