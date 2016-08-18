<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $status
 * @property string $text
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'required'],
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
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'text' => Yii::t('app', 'Text'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
