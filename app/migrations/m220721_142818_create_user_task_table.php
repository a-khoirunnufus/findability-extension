<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_task}}`.
 */
class m220721_142818_create_user_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_task', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->notNull(),
            'name' => $this->string()->notNull(),
            'order' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-user_task-user_id',
            'user_task',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_task-user_id',
            'user_task',
        );

        $this->dropTable('user_task');
    }
}
