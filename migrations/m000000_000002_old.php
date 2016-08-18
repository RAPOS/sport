<?php

use yii\db\Migration;

class m000000_000002_old extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                    => $this->primaryKey(),
            'email'                 => $this->string()->unique(),
            'email_status'          => $this->smallInteger(4),
            'phone'                 => $this->string(20)->unique(),
            'auth_key'              => $this->string(32),
            'password_hash'         => $this->string(),
            'password_reset_token'  => $this->string(),
            'email_confirm_token'   => $this->string(),
            'avatar_label'          => $this->string(10)->defaultValue('avatar'),
            'b_date'                => $this->integer(),
            'last_name'             => $this->string(30),
            'first_name'            => $this->string(30),
            'phisical_address'      => $this->string(),
            'company_name'          => $this->string(30),
            'entity_address'        => $this->string(),
            'description'           => $this->text(),
            'sex'                   => $this->boolean()->defaultValue(null),
            'city_id'               => $this->integer(),
            'country_id'            => $this->integer(),
            'id_address'            => $this->string(15),                       // ?
            'status'                => $this->smallInteger(1)->defaultValue(0),
            'preference_subscribe'  => $this->boolean()->defaultValue(true),
            'identification_code'   => $this->string(30),
            'authKey'               => $this->string(100),
            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('user_city_fk', '{{%user}}', 'city_id', '{{%geo_city}}', 'id', 'CASCADE');
        $this->addForeignKey('user_country_fk', '{{%user}}', 'country_id', '{{%geo_country}}', 'id', 'CASCADE');

        $this->createTable('{{%user_profile}}', [
            'user_id'               => $this->primaryKey(),
            'address'               => $this->string(50),
            'phone'                 => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('user_profile_user_fk', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createTable('{{%user_online}}', [
            'user_id'           => $this->primaryKey(),
            'online'            => $this->integer()
        ], $tableOptions);


        $this->addForeignKey('user_online_user_fk', '{{%user_online}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createTable('{{%blog}}', [
            'id'                    => $this->primaryKey(),
            'title'                 => $this->string(),
            'text'                  => $this->text(),
            'user_id'               => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('blog_user_fk', '{{%blog}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        /* жалобы */
        $this->createTable('{{%complaints}}', [
            'id'                    => $this->primaryKey(),
            'user_id'               => $this->integer(),
            'type'                  => $this->smallInteger(4),
            'object_id'             => $this->integer(),                    // ?
            'text'                  => $this->text(),
            'status'                => $this->smallInteger(2),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('complaints_user_fk', '{{%complaints}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        /* диалоги */
        $this->createTable('{{%dialog}}', [
            'id'                    => $this->primaryKey(),
            'user_write'            => $this->integer()->notNull(),
            'user_read'             => $this->integer()->notNull(),
            'message'               => $this->text(),
            'is_read'               => $this->boolean()->defaultValue(false),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('dialog_user_write_fk', '{{%dialog}}', 'user_write', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('dialog_user_read_fk', '{{%dialog}}', 'user_read', '{{%user}}', 'id', 'CASCADE');

        /* место */
        $this->createTable('{{%place}}', [
            'id'                    => $this->primaryKey(),
            'name'                  => $this->string()->notNull(),
            'address'               => $this->string(),
            'status'                => $this->smallInteger(2),
            'count_views'           => $this->integer(),
            'is_gym'                => $this->smallInteger(4),
            'city_id'               => $this->integer()->notNull(),
            'user_id'               => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('place_user_fk', '{{%place}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('place_city_fk', '{{%place}}', 'city_id', '{{%geo_city}}', 'id', 'CASCADE');

        /* изображения мест */
        $this->createTable('{{%place_gallery}}', [
            'id'                    => $this->primaryKey(),
            'image'                 => $this->string(),
            'place_id'              => $this->integer()->notNull(),
            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('place_gallery_place_fk', '{{%place_gallery}}', 'place_id', '{{%place}}', 'id', 'CASCADE');

        /* тип события */
        $this->createTable('{{%event_type}}', [
            'id'                    => $this->primaryKey(),
            'name'                  => $this->string()->notNull(),
            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->batchInsert('{{%event_type}}', ['id', 'name', 'created_at', 'updated_at'],
            [
                [1, 'Футбол', time(), time()],
                [2, 'Хоккей', time(), time()],
                [3, 'Шахматы', time(), time()],
                [4, 'Плавание', time(), time()],
                [5, 'Теннис', time(), time()],
            ]);

        /* события */
        $this->createTable('{{%event}}', [
            'id'                    => $this->primaryKey(),
            'type'                  => $this->smallInteger(4),
            'price'                 => $this->money(11, 2),
            'date_start'            => $this->integer()->notNull(),
            'date_end'              => $this->integer()->notNull(),
            'count_place'           => $this->integer(),
            'free_count_place'      => $this->integer(),
            'description'           => $this->string(500),
            'min_count_place'       => $this->integer(),
            'max_count_place'       => $this->integer(),
            'coach'                 => $this->smallInteger(4),
            'duration'              => $this->integer(),
            'constantly_day'        => $this->string(),
            'constantly_time'       => $this->string(),
            'count_views'           => $this->integer(),
            'recalculate_price'     => $this->smallInteger(4),
            'status'                => $this->smallInteger(2),
            'city_id'               => $this->integer()->notNull(),
            'event_type'            => $this->integer()->notNull(),
            'place_id'              => $this->integer()->notNull(),
            'user_id'               => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('event_user_fk', '{{%event}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('event_event_type_fk', '{{%event}}', 'event_type', '{{%event_type}}', 'id', 'CASCADE');
        $this->addForeignKey('event_place_fk', '{{%event}}', 'place_id', '{{%place}}', 'id', 'CASCADE');
        $this->addForeignKey('event_city_fk', '{{%event}}', 'city_id', '{{%geo_city}}', 'id', 'CASCADE');

        /* Расписание */
        $this->createTable('{{%gym_info}}', [
            'id'                    => $this->primaryKey(),
            'schedule'              => $this->string(500),
            'price_per_hour'        => $this->integer(),
            'place_id'              => $this->integer()->notNull(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('gym_info_place_fk', '{{%gym_info}}', 'place_id', '{{%place}}', 'id', 'CASCADE');

        /* Уведомления */
        $this->createTable('{{%notification}}', [
            'id'                    => $this->primaryKey(),
            'type'                  => $this->smallInteger(4),
            'status'                => $this->smallInteger(4),
            'text'                  => $this->text(),
            'user_id'               => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('notification_user_fk', '{{%notification}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        /* Уведомления */
        $this->createTable('{{%preferences}}', [
            'id'                    => $this->primaryKey(),
            'type'                  => $this->smallInteger(4),
            'object_id'             => $this->integer(),
            'user_id'               => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('preferences_user_fk', '{{%preferences}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        /* Уведомления */
        $this->createTable('{{%request}}', [
            'id'                        => $this->primaryKey(),
            'status'                    => $this->smallInteger(4),
            'comment_for_participant'   => $this->smallInteger(4),
            'comment_for_owner'         => $this->smallInteger(4),
            'event_id'                  => $this->integer(),
            'user_id'                   => $this->integer(),
            'created_at'                => $this->integer()->notNull(),
            'updated_at'                => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('request_user_fk', '{{%request}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('request_event_fk', '{{%request}}', 'event_id', '{{%event}}', 'id', 'CASCADE');

        /* комментарии */
        $this->createTable('{{%comments}}', [
            'id'                    => $this->primaryKey(),
            'object_id'             => $this->integer(),                    // ?
            'comment'               => $this->text(),
            'rating'               => $this->float(),
            'status'                => $this->smallInteger(2),
            'user_id'               => $this->integer(),
            'type'                  => $this->smallInteger(4),
            'request_id'            => $this->integer(),
            'created_at'            => $this->integer()->notNull(),
            'updated_at'            => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('comments_user_fk', '{{%comments}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('comments_request_fk', '{{%comments}}', 'request_id', '{{%request}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%request}}');
        $this->dropTable('{{%preferences}}');
        $this->dropTable('{{%notification}}');
        $this->dropTable('{{%gym_info}}');
        $this->dropTable('{{%event}}');
        $this->dropTable('{{%event_type}}');
        $this->dropTable('{{%place_gallery}}');
        $this->dropTable('{{%place}}');
        $this->dropTable('{{%complaints}}');
        $this->dropTable('{{%comments}}');
        $this->dropTable('{{%blog}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user}}');
    }
}
