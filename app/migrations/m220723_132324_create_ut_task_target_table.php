<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ut_task_target}}`.
 */
class m220723_132324_create_ut_task_target_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ut_task_target', [
            'id' => $this->primaryKey(),
            'file_id' => $this->string()->notNull(),
            'path_to_file' => $this->string(),
            'file_depth' => $this->integer(),
            'description' => $this->string(),
            'frequency' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-ut_task_target-task_id',
            'ut_task_target',
            'task_id',
            'ut_task',
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
            'fk-ut_task_target-task_id',
            'ut_task_target',
        );

        $this->dropTable('ut_task_target');
    }
}
