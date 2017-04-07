<?php
namespace app\components;


use Yii;
use yii\base\ActionFilter;

class AccessFilter extends ActionFilter
{
    const LEVEL_NONE       =0;// 0000
    const LEVEL_MODEL      =1;// 0001
    const LEVEL_ACTION     =2;// 0010
    const LEVEL_CONTROLLER =4;// 0100
    const LEVEL_MODULE     =8;// 1000
    //const LEVEL_APP        =16;//1000 0000

    public $level=0;

    public $model;
    //public $controller;
    //public $view;

    public function beforeAction($action)
    {
        if($this->level&static::LEVEL_MODEL && $this->checkModelAccess($action)==false) return false;
        if($this->level&static::LEVEL_ACTION && $this->checkActionAccess($action)==false) return false; 
        if($this->level&static::LEVEL_CONTROLLER && $this->checkContollerAccess($action)==false) return false;
        if($this->level&static::LEVEL_MODULE && $this->checkModuleAccess($action)==false) return false;
        return parent::beforeAction($action);
    }

    protected function checkModelAccess($action)
    {
        $modelName=(new \ReflectionClass($this->model))->getShortName();
        if(!\Yii::$app->user->can($action->id.$modelName)) {
            throw new \yii\web\ForbiddenHttpException("Forbidden {$action->id} model:{$modelName}");
        }
        return true;
    }

    protected function checkActionAccess($action)
    {
        $controller=$action->controller->id;
        if(!\Yii::$app->user->can($controller.'::'.$action->id)) {
            throw new \yii\web\ForbiddenHttpException("Forbidden access action:{$controller}::{$action->id}");
        }
        return true;
    }

    protected function checkContollerAccess($action)
    {
        $controller=$action->controller->id;
        //$controllerName=(new \ReflectionClass($this->controller))->getShortName();
        if(!\Yii::$app->user->can($controller)) {
            throw new \yii\web\ForbiddenHttpException("Forbidden access controller:{$controller}");
        }
        return true;
    }

    protected function checkModuleAccess($action)
    {
        $module=$action->controller->module->id;
        if(!\Yii::$app->user->can($module)) {
            throw new \yii\web\ForbiddenHttpException("Forbidden access module:{$module}");
        }
    }

}