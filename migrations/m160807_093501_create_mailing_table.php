<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mailing`.
 */
class m160807_093501_create_mailing_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailing', [
            'user_id'               => $this->primaryKey(),
            'private'               => $this->boolean()->defaultValue(true),
            'accept_declate_event'  => $this->boolean()->defaultValue(true),
            'new_bid_my_event'      => $this->boolean()->defaultValue(true),
            'event_soon'            => $this->boolean()->defaultValue(true),
            'event_for_me'          => $this->boolean()->defaultValue(true),
        ]);

        $this->addForeignKey('mailing_user_fk', '{{%mailing}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mailing');
    }
}
