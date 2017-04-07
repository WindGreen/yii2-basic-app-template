<?php

use yii\db\Migration;

class m170407_025739_add_authenticaiton_session_table extends Migration
{
    public function up()
    {
        $sql=<<<'EOT'
-- -----------------------------------------------------
-- Table `authentication`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `authentication` (
  `user_id` INT NOT NULL,
  `identity_type` SMALLINT NOT NULL,
  `identifier` VARCHAR(64) NOT NULL,
  `credential` VARCHAR(128) NOT NULL,
  `expire_at` INT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  INDEX `fk_user_login_user_idx` (`user_id` ASC),
  PRIMARY KEY (`identity_type`, `user_id`),
  UNIQUE INDEX `idx_type_identifier_unq` (`identity_type` ASC, `identifier` ASC),
  CONSTRAINT `fk_user_login_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `session`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `session` (
  `user_id` INT NOT NULL,
  `identity_type` SMALLINT NOT NULL,
  `identifier` VARCHAR(64) NOT NULL,
  `token` VARCHAR(128) NOT NULL,
  `expire_at` INT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`user_id`, `identity_type`),
  INDEX `fk_session_user_login1_idx` (`user_id` ASC, `identity_type` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_session_user_login1`
    FOREIGN KEY (`user_id` , `identity_type`)
    REFERENCES `authentication` (`user_id` , `identity_type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
EOT;
        $this->execute($sql);
    }

    public function down()
    {
        echo "m170407_025739_add_authenticaiton_session_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
