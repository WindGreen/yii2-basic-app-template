<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;

/**
 * 检查是否匹配用户的组
 */
class UserTypeRule extends Rule
{
    public $name = 'userType';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $type = Yii::$app->user->identity->user->type;
            if ($item->name === 'admin') {
                return $type == User::TYPE_ADMIN;
            } elseif ($item->name === 'platform') {
                return $type == User::TYPE_ADMIN || $type == User::TYPE_PLATFORM;//管理员和平台账号拥有平台账号级别权限
            } elseif ($item->name === 'customer'){
                return $type == User::TYPE_ADMIN || $type == User::TYPE_CUSTOMER;//管理员和普通用户拥有普通用户的管理权限
            }
        }
        return false;
    }
}
