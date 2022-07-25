<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ut_task_ref}}`.
 */
class m220722_102512_create_ut_task_ref_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ut_task_ref', [
            'interface' => $this->string()->notNull(),
            'code' => $this->string(3)->unique()->notNull(),
            'name' => $this->string()->notNull(),
            'hint_visible' => $this->boolean()->notNull(),
            'order' => $this->integer(),
        ]);

        $this->insert('ut_task_ref', [
            'interface' => 'GOOGLE_DRIVE',
            'code' => 'GH',
            'name' => 'Antarmuka Google Drive dengan Petunjuk',
            'hint_visible' => true,
            'order' => 1,
        ]);
        $this->insert('ut_task_ref', [
            'interface' => 'GOOGLE_DRIVE',
            'code' => 'G',
            'name' => 'Antarmuka Google Drive tanpa Petunjuk',
            'hint_visible' => false,
            'order' => 2,
        ]);
        $this->insert('ut_task_ref', [
            'interface' => 'QUICKNAV',
            'code' => 'QH',
            'name' => 'Antarmuka QuickNav dengan Petunjuk',
            'hint_visible' => true,
            'order' => 3,
        ]);
        $this->insert('ut_task_ref', [
            'interface' => 'QUICKNAV',
            'code' => 'Q',
            'name' => 'Antarmuka QuickNav tanpa Petunjuk',
            'hint_visible' => false,
            'order' => 4,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('ut_task_ref', ['code' => 'GH']);
        $this->delete('ut_task_ref', ['code' => 'G']);
        $this->delete('ut_task_ref', ['code' => 'QH']);
        $this->delete('ut_task_ref', ['code' => 'G']);
        $this->dropTable('ut_task_ref');
    }
}
