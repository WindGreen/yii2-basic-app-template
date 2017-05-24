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

        $perm = $auth->createPermission('site');
        $perm->description='控制器site';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $perm=$auth->createPermission('user');
        $perm->description='控制器user';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $perm=$auth->createPermission('auth-assignment');
        $perm->description='控制器auth-assignment';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $perm=$auth->createPermission('authentication');
        $perm->description='控制器authentication';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $perm=$auth->createPermission('auth-item');
        $perm->description='控制器auth-item';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $perm=$auth->createPermission('auth-rule');
        $perm->description='控制器auth-rule';
        $auth->add($perm);
        $auth->addChild($adminRole,$perm);

        $auth->assign($authRole,$form->user->id);
        
    }

    public function safeDown()
    {
        echo "m170523_123730_add_user_and_auth cannot be reverted.\n";

        return false;
    }
    
}
