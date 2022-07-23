<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ut_participant_file_structure}}`.
 */
class m220723_044911_create_ut_participant_file_structure_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ut_participant_file_structure}}', [
            'id' => $this->primaryKey(),
            'file_hierarchy' => $this->text(),
            'files_per_depth' => $this->text(),
            'file_counts_per_depth' => $this->text(),
            'participant_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-ut_participant_file_structure-participant_id',
            'ut_participant_file_structure',
            'participant_id',
            'ut_participant',
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
            'fk-ut_participant_file_structure-participant_id',
            'ut_participant_file_structure',
        );

        $this->dropTable('{{%ut_participant_file_structure}}');
    }
}
