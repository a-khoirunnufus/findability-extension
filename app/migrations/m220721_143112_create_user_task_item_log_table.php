<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_task_item_log}}`.
 */
class m220721_143112_create_user_task_item_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_task_item_log', [
            'id' => $this->primaryKey(),
            'action' => $this->string()->notNull(),
            'object' => $this->string()->notNull(),
            'time' => $this->timestamp()->notNull(),
            'task_item_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-user_task_item_log-task_item_id',
            'user_task_item_log',
            'task_item_id',
            'user_task_item',
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
            'fk-user_task_item_log-task_item_id',
            'user_task_item_log',
        );

        $this->dropTable('user_task_item_log');
    }
}
