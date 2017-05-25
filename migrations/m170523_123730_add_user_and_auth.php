<?php

use yii\db\Migration;

use app\models\SignupForm;

class m170523_123730_add_user_and_auth extends Migration
{
/*    
    public function up()
    {

    }

    public function down()
    {
        echo "m170523_123730_add_user_and_auth cannot be reverted.\n";

        return false;
    }
*/
    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        //创建 User
        $form=new SignupForm;
        $form->username='admin123';
        $form->password='bilibili';
        $form->signup();

        //创建权限
        $auth = Yii::$app->authManager;

        $adminRole = $auth->createRole('admin');
        $adminRole->description='管理员角色';
        $auth->add($adminRole);


        $app = $auth->createPermission('app:app');
        $app->description='整个系统权限';
        $auth->add($app);
        $auth->addChild($adminRole,$app);


        $module = $auth->createPermission('module:app');
        $module->description='物理模块app';
        $auth->add($module);
        $auth->addChild($app,$module);

        $auth_module = $auth->createPermission('sub-module:auth');
        $auth_module->description='逻辑模块-权限管理';
        $auth->add($auth_module);
        $auth->addChild($module,$auth_module);

        $perm=$auth->createPermission('controller:auth-assignment');
        $perm->description='控制器auth-assignment';
        $auth->add($perm);
        $auth->addChild($auth_module,$perm);

        $perm=$auth->createPermission('controller:auth-item-child');
        $perm->description='控制器auth-item-child';
        $auth->add($perm);
        $auth->addChild($auth_module,$perm);

            $perm=$auth->createPermission('auth-item-child::index');
            $perm->description='权限关系列表';
            $auth->add($perm);
            $auth->addChild($auth_module,$perm);

                $perm2=$auth->createPermission('auth-item-child::create');
                $perm2->description='添加权限关系';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

                $perm2=$auth->createPermission('auth-item-child::update');
                $perm2->description='更新权限关系';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

                $perm2=$auth->createPermission('auth-item-child::delete');
                $perm2->description='删除权限关系';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

        $perm=$auth->createPermission('controller:auth-item');
        $perm->description='控制器auth-item';
        $auth->add($perm);
        $auth->addChild($auth_module,$perm);

            $perm=$auth->createPermission('auth-item::index');
            $perm->description='权限列表';
            $auth->add($perm);
            $auth->addChild($auth_module,$perm);

                $perm2=$auth->createPermission('auth-item::create');
                $perm2->description='添加权限';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

                $perm2=$auth->createPermission('auth-item::update');
                $perm2->description='更新权限';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

                $perm2=$auth->createPermission('auth-item::delete');
                $perm2->description='删除权限';
                $auth->add($perm2);
                $auth->addChild($perm,$perm2);

        $perm=$auth->createPermission('controller:auth-rule');
        $perm->description='控制器auth-rule';
        $auth->add($perm);

        $perm=$auth->createPermission('controller:authentication');
        $perm->description='控制器authentication';
        $auth->add($perm);
        $auth->addChild($auth_module,$perm);


        $perm = $auth->createPermission('controller:site');
        $perm->description='控制器site';
        $auth->add($perm);
        $auth->addChild($module,$perm);

        $perm2 = $auth->createPermission('site::index');
        $perm2->description='首页';
        $auth->add($perm2);
        $auth->addChild($perm,$perm2);


        $perm=$auth->createPermission('controller:user');
        $perm->description='控制器user';
        $auth->add($perm);
        $auth->addChild($module,$perm);

        $auth->assign($adminRole,$form->user->id);
        
    }

    public function safeDown()
    {
        return true;
        $this->truncateTable('auth_assignment');
        $this->truncateTable('auth_item_child');
        $this->truncateTable('auth_item');
        $this->truncateTable('auth_rule');
    }
    
}
