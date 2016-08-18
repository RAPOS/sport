<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_event_type`.
 */
class m160807_090403_create_user_event_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* тип события */
        $this->createTable('{{%user_event_type}}', [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer(),
            'event_type_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('event_type_user_fk0', '{{%user_event_type}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('event_event_type_fk0', '{{%user_event_type}}', 'event_type_id', '{{%event_type}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_event_type');
    }
}
