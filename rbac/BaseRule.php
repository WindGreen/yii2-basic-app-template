<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;


class BaseRule extends Rule
{
    public $name = 'base';

    public function execute($user, $item, $params)
    {
        $actionModel = preg_split('/(?=[A-Z])/', $item->name);
        if(method_exists($this, $item->name)) return call_user_func([$this,$item->name],$user,$params);
        else return call_user_func([$this,$actionModel[0]],$user,$actionModel[1],$params);
    }

    protected function view($user,$model,$params)
    {
        if(isset($params[$model]) && isset($params[$model]->id)){
            return $params[$model]->id==$user;
        }else return false;
    }

    protected function create($user,$model,$params)
    {
        return true;
    }

    protected function update($user,$model,$params)
    {
        if(isset($params[$model]) && isset($params[$model]->id)){
            return $params[$model]->created_by==$user;
        }else return false;
    }

    protected function delete($user,$model,$params)
    {
        if(isset($params[$model]) && isset($params[$model]->id)){
            return $params[$model]->created_by==$user;
        }else return false;
    }

    protected function index($user,$model,$params)
    {
        if(isset($params[$model]) && isset($params[$model]->id)){
            return $params[$model]->created_by==$user;
        }else return false;
    }

    protected function viewUser($user,$params)
    {
        return isset($params['User']) ? $params['User']->id == $user : true;
    }

    protected function updateUser($user,$params)
    {
        return isset($params['User']) ? $params['User']->id == $user : true;
    }
}
