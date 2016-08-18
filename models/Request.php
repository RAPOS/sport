<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $comment_for_participant
 * @property integer $comment_for_owner
 * @property integer $event_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Comments[] $comments
 * @property Event $event
 * @property User $user
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'comment_for_participant', 'comment_for_owner', 'event_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'comment_for_participant' => Yii::t('app', 'Comment For Participant'),
            'comment_for_owner' => Yii::t('app', 'Comment For Owner'),
            'event_id' => Yii::t('app', 'Event ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
