<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dialog".
 *
 * @property integer $id
 * @property integer $user_write
 * @property integer $user_read
 * @property string $message
 * @property integer $is_read
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $userRead
 * @property User $userWrite
 */
class Dialog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dialog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_write', 'user_read', 'created_at', 'updated_at'], 'required'],
            [['user_write', 'user_read', 'is_read', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string'],
            [['user_read'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_read' => 'id']],
            [['user_write'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_write' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_write' => Yii::t('app', 'User Write'),
            'user_read' => Yii::t('app', 'User Read'),
            'message' => Yii::t('app', 'Message'),
            'is_read' => Yii::t('app', 'Is Read'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRead()
    {
        return $this->hasOne(User::className(), ['id' => 'user_read']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserWrite()
    {
        return $this->hasOne(User::className(), ['id' => 'user_write']);
    }
}
