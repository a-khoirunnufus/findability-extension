<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_task_item}}`.
 */
class m220721_143008_create_user_task_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_task_item', [
            'id' => $this->primaryKey(),
            'code' => $this->string(7),
            'file_id' => $this->string()->notNull(),
            'file_name' => $this->string()->notNull(),
            'path_to_file' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'order' => $this->integer(),
            'is_complete' => $this->boolean()->defaultValue(false),
            'completed_at' => $this->timestamp(),
            'task_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-user_task_item-task_id',
            'user_task_item',
            'task_id',
            'user_task',
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
            'fk-user_task_item-task_id',
            'user_task_item',
        );

        $this->dropTable('user_task_item');
    }
}
