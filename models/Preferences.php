<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preferences".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $object_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Preferences extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preferences';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'object_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
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
            'object_id' => Yii::t('app', 'Object ID'),
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
