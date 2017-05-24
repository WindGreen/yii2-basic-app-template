<?php

namespace app\components;

use \app\models\UserIdentity;
use \yii\filters\auth\CompositeAuth;
use \yii\filters\auth\HttpBasicAuth;
use \app\components\AccessFilter;

class RestController extends \yii\rest\ActiveController
{
    /**
     * 数据级别的访问控制 这个可以合并到AccessFileter里面去吗？
     * 
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if(in_array($action, $this->behaviors()['access']['except']))
            return true;

        if($model==null) $model=$this->modelClass;
        $modelName=(new \ReflectionClass($model))->getShortName();

        $params['actionName']=$action;
        $params['model']=$model;
        if(!isset($params['modelName']))
            $params['modelName']=$modelName;
        //if(!isset($params['identity']))
            //$params['identity']= count($model->primaryKey())>0 ? $model->primaryKey()[0] : null;

        if(!\Yii::$app->user->can($action.$modelName,$params)) {
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
                    //'auth'=>[ApiIdentity::className(),'findIdentityByAccessToken']
                ],
                [
                    'class'=>SignatureAuth::className(),
                    'auth'=>[ApiIdentity::className(),'findIdentityByIdentifier']
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
