<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mailing".
 *
 * @property integer $user_id
 * @property integer $private
 * @property integer $accept_declate_event
 * @property integer $new_bid_my_event
 * @property integer $event_soon
 * @property integer $event_for_me
 *
 * @property User $user
 */
class Mailing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailing';
    }

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
            'private' => Yii::t('app', 'Private'),
            'accept_declate_event' => Yii::t('app', 'Accept Declate Event'),
            'new_bid_my_event' => Yii::t('app', 'New Bid My Event'),
            'event_soon' => Yii::t('app', 'Event Soon'),
            'event_for_me' => Yii::t('app', 'Event For Me'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
