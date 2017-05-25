<?php

namespace app\components;

use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\AccessFilter;

class WebController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']=[
            'class'=>AccessFilter::className(),
            'level'=>AccessFilter::LEVEL_ACTION,
            'except'=>[
                //这个地方定义的操作在 \yii\base\ActionFilter.php:143 isActive()里做判断
                //如果定义了这个操作，则不进行beforeAction检查
                'error', //error action, checked in ActionFilter.php:isActive line:143
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ];
        return $behaviors;
    } 
}