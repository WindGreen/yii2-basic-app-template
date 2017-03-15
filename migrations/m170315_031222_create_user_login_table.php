<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_login`.
 */
class m170315_031222_create_user_login_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_login', [
            'user_id'       =>$this->integer()->notNull(),
            'identify_type' =>$this->smallInteger()->notNull(),
            'identifier'    =>$this->string(128)->notNull(),
            'credential'    =>$this->string(128)->notNull(),
            'expire_at'     =>$this->integer()->notNull()->defaultValue(0),
            'created_at'    =>$this->integer(),
            'updated_at'    =>$this->integer(),
        ]);

        $this->addPrimaryKey(
            'pk-user_login-user_id-identify_type',
            'user_login',
            ['user_id','identify_type']
        );

        $this->createIndex(
            'idx-user_login-user_id',
            'user_login',
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_login-user_id',
            'user_login',
            'user_id',
            'user',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            'idx-user_login-identify_type',
            'user_login',
            'identify_type'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_login');
    }
}
