<?php

namespace app\components;

use \app\models\UserIdentity;
use \yii\filters\auth\CompositeAuth;
use \yii\filters\auth\HttpBasicAuth;
use \app\components\AccessFilter;

class RestController extends \yii\rest\ActiveController
{
    /**
     * 数据级别的访问控制
     * 
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if($model==null) $model=$this->modelClass;
        $modelName=(new \ReflectionClass($model))->getShortName();
        $checkParams = $model ? [$modelName=>$model] : null;
        if(!\Yii::$app->user->can($action.$modelName,$checkParams)) {
            throw new \yii\web\ForbiddenHttpException("Forbidden $action the $modelName");
        }   
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class'=>HttpBasicAuth::className(),
                    //'auth'=>[ApiIdentity::className(),'validateUidAndToken']
                    'auth'=>[UserIdentity::className(),'findIdentityByAccessToken']
                ]                
            ],
        ];
        $behaviors['access']=[
            'class'=>AccessFilter::className(),
            'level'=>AccessFilter::LEVEL_NONE,
            'model'=>$this->modelClass,
        ];
        return $behaviors;
    }
}
