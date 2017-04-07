<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_and_auth`.
 */
class m170327_120017_create_user_and_auth_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql=<<<'EOT'
-- -----------------------------------------------------
-- Table `auth_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  `type` SMALLINT(6) NOT NULL,
  `description` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `rule_name` VARCHAR(64) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `data` BLOB NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `rule_name` (`rule_name` ASC),
  INDEX `idx-auth_item-type` (`type` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uid` CHAR(32) NOT NULL,
  `username` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' NULL,
  `status` SMALLINT(6) NOT NULL DEFAULT '10',
  `created_at` INT(11) NULL,
  `updated_at` INT(11) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username` (`username` ASC),
  UNIQUE INDEX `uid` (`uid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `auth_assignment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  `user_id` INT(11) NOT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  INDEX `fk_user_id_idx` (`user_id` ASC),
  CONSTRAINT `fk_auth_assignment_item_name`
    FOREIGN KEY (`item_name`)
    REFERENCES `auth_item` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_auth_assignment_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `auth_item_child`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  `child` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `child` (`child` ASC),
  CONSTRAINT `fk_auth_item_child_parent`
    FOREIGN KEY (`parent`)
    REFERENCES `auth_item` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_auth_item_child_child`
    FOREIGN KEY (`child`)
    REFERENCES `auth_item` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `auth_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  `data` BLOB NULL DEFAULT NULL,
  `created_at` INT(11) NULL DEFAULT NULL,
  `updated_at` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;
EOT;
        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "cannot be reverted.\n";

        return false;
    }
}
