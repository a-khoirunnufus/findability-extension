<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_task_ref}}`.
 */
class m220721_143452_create_user_task_ref_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_task_ref', [
            'code' => $this->string(3)->unique()->notNull(),
            'name' => $this->string()->notNull(),
            'order' => $this->integer(),
        ]);

        $this->insert('user_task_ref', [
            'code' => 'GH',
            'name' => 'Antarmuka Google Drive dengan Petunjuk',
            'order' => 1,
        ]);
        $this->insert('user_task_ref', [
            'code' => 'G',
            'name' => 'Antarmuka Google Drive tanpa Petunjuk',
            'order' => 2,
        ]);
        $this->insert('user_task_ref', [
            'code' => 'QH',
            'name' => 'Antarmuka QuickNav dengan Petunjuk',
            'order' => 3,
        ]);
        $this->insert('user_task_ref', [
            'code' => 'Q',
            'name' => 'Antarmuka QuickNav tanpa Petunjuk',
            'order' => 4,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user_task_ref', ['code' => 'GH']);
        $this->delete('user_task_ref', ['code' => 'G']);
        $this->delete('user_task_ref', ['code' => 'QH']);
        $this->delete('user_task_ref', ['code' => 'G']);
        $this->dropTable('user_task_ref');
    }
}
