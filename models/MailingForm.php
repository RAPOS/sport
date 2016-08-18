<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 07.08.2016
 * Time: 14:55
 */

namespace app\models;

use Yii;

class MailingForm extends Mailing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['private', 'accept_declate_event', 'new_bid_my_event', 'event_soon', 'event_for_me'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'private' => Yii::t('app', 'Пришло личное сообщение'),
            'accept_declate_event' => Yii::t('app', 'Я был принят или отклонен на событие'),
            'new_bid_my_event' => Yii::t('app', 'Поступила новая заявка на созданное мной событие'),
            'event_soon' => Yii::t('app', 'У Вас скоро событие (напоминание)'),
            'event_for_me' => Yii::t('app', 'Появилось событие, которое я искал (см. таблицу ниже)'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }
}