<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\userId;


class BaseRule extends Rule
{
    public $name = 'base';

    public function execute($userId, $item, $params)
    {
        $actionModel = preg_split('/(?=[A-Z])/', $item->name);
        $actionName= isset($params['actionName']) ? $params['actionName'] : $actionModel[0];
        //$modelName= isset($params['modelName']) ? $params['modelName'] : substr($item->name, strlen($actionName));

        if(method_exists($this, $item->name)) 
            return call_user_func([$this,$item->name],$userId,$params);
        else if(method_exists($this, $actionName))
            return call_user_func([$this,$actionName],$userId,$params);
        else
            return false;
    }

    private function _viewAndModify($userId,$params)
    {
        if(isset($params['model'])){
            $identity;
            if(isset($params['identity']) && isset($params['model']->$params['identity']))
                $identity=$params['model']->$params['identity'];
            else if(isset($params['model']->created_by))
                $identity=$params['model']->created_by;
            else return false;

            return $userId==$identity;
        }
    }

    protected function view($userId,$params)
    {
        return $this->_viewAndModify($userId,$params);
    }

    protected function create($userId,$params)
    {
        return true;
    }

    protected function update($userId,$params)
    {
        return $this->_viewAndModify($userId,$params);
    }

    protected function delete($userId,$params)
    {
        return $this->_viewAndModify($userId,$params);
    }

    protected function index($userId,$params)
    {
        return $this->_viewAndModify($userId,$params);
    }
}
