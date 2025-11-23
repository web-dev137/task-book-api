<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m251117_163648_create_user_table extends Migration
{
       /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string()->notNull()->unique(),
            'is_archived' => $this->boolean()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()],
            'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
