<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170315_025359_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id'         =>$this->primaryKey(),
            'uid'        =>$this->char(32)->notNull()->unique(),
            'phone'      =>$this->string(16)->notNull()->defaultValue(''),
            'username'   =>$this->string(64)->notNull(),
            'avatar'     =>$this->string(1024)->notNull()->defaultValue(''),
            'status'     =>$this->smallInteger()->notNull()->defaultValue('10'),
            'created_at' =>$this->integer(),
            'updated_at' =>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
