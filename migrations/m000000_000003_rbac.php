<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use app\models\AuthItem;

/**
 * Initializes RBAC tables
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m000000_000003_rbac extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_rule}}', [
            'name'          => $this->string(64)->notNull(),
            'data'          => $this->text(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),

        ], $tableOptions);

        $this->addPrimaryKey('auth_rule_pk', '{{%auth_rule}}', 'name');

        $this->batchInsert('{{%auth_rule}}', ['name', 'data', 'created_at', 'updated_at'],
            [
                ['authorRule', 'O:31:"app\components\rbac\AuthorRule":3:{s:4:"name";s:11:"authorRule";s:9:"createdAt";N;s:9:"updatedAt";N;}', time(), time()]
            ]);

        $this->createTable('{{%auth_item}}', [
            'name'          => $this->string(64),
            'type'          => $this->integer()->notNull(),
            'description'   => $this->text()->notNull(),
            'rule_name'     => $this->string(64),
            'data'          => $this->text(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer()
        ], $tableOptions);

        $this->addPrimaryKey('auth_item_name_pk', '{{%auth_item}}', 'name');
        $this->addForeignKey('auth_item_rule_name_fk', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}',  'name', 'SET NULL', 'CASCADE');
        $this->createIndex('auth_item_type_index', '{{%auth_item}}', 'type');

        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], [
            ['creator', AuthItem::TYPE_ROLE, 'Создатель', NULL, NULL, time(), time()],
            ['admin', AuthItem::TYPE_ROLE, 'Администратор', NULL, NULL, time(), time()],
            ['redactor', AuthItem::TYPE_ROLE, 'Редактор сайта', NULL, NULL, time(), time()],
            ['office', AuthItem::TYPE_ROLE, 'Юридическое лицо', NULL, NULL, time(), time()],
            ['user', AuthItem::TYPE_ROLE, 'Пользователь', NULL, NULL, time(), time()],
            ['userCreate', AuthItem::TYPE_PERMISSION, 'Создавать пользователя', NULL, NULL, time(), time()],
            ['userUpdate', AuthItem::TYPE_PERMISSION, 'Редактировать пользователя', NULL, NULL, time(), time()],
            ['userDelete', AuthItem::TYPE_PERMISSION, 'Удалять пользователя', NULL, NULL, time(), time()],
            ['userView', AuthItem::TYPE_PERMISSION, 'Просматривать пользователя', NULL, NULL, time(), time()],
            ['eventCreate', AuthItem::TYPE_PERMISSION, 'Создавать событие', NULL, NULL, time(), time()],
            ['eventUpdate', AuthItem::TYPE_PERMISSION, 'Редактировать событие', NULL, NULL, time(), time()],
            ['eventDelete', AuthItem::TYPE_PERMISSION, 'Удалять событие', NULL, NULL, time(), time()],
            ['eventView', AuthItem::TYPE_PERMISSION, 'Просматривать событие', NULL, NULL, time(), time()],
            ['placeCreate', AuthItem::TYPE_PERMISSION, 'Создавать место', NULL, NULL, time(), time()],
            ['placeUpdate', AuthItem::TYPE_PERMISSION, 'Редактировать место', NULL, NULL, time(), time()],
            ['placeDelete', AuthItem::TYPE_PERMISSION, 'Удалять место', NULL, NULL, time(), time()],
            ['placeView', AuthItem::TYPE_PERMISSION, 'Просматривать место', NULL, NULL, time(), time()],
            ['authorRule', AuthItem::TYPE_PERMISSION, 'Изменять свои записи', 'authorRule', NULL, time(), time()],
        ]);

        $this->createTable('{{%auth_item_child}}', [
            'parent'    => $this->string(64)->notNull(),
            'child'     => $this->string(64)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('auth_item_child_pk', '{{%auth_item_child}}', array('parent', 'child'));
        $this->addForeignKey('auth_item_child_parent_fk', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_item_child_child_fk', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['redactor', 'user'],
            ['redactor', 'office'],
            ['redactor', 'eventCreate'],
            ['redactor', 'eventUpdate'],
            ['redactor', 'eventDelete'],
            ['redactor', 'eventView'],
            ['redactor', 'placeCreate'],
            ['redactor', 'placeUpdate'],
            ['redactor', 'placeDelete'],
            ['redactor', 'placeView'],
            ['admin', 'redactor'],
            ['admin', 'userCreate'],
            ['admin', 'userUpdate'],
            ['admin', 'userDelete'],
            ['admin', 'userView'],
            ['creator', 'admin'],
        ]);

        $this->createTable('{{%auth_assignment}}', [
            'item_name'     => $this->string(64)->notNull(),
            'user_id'       => $this->integer()->notNull(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ], $tableOptions);

        $this->addPrimaryKey('auth_assignment_pk', '{{%auth_assignment}}', array('item_name', 'user_id'));
        $this->addForeignKey('auth_assignment_item_name_fk', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_assignment_user_id_fk', '{{%auth_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
    }
}
