<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "photo".
 *
 * @property integer $id
 * @property string $file
 * @property string $file_small
 * @property string $type
 * @property integer $object_id
 * @property integer $user_id
 * @property integer $deleted
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file', 'file_small', 'type'], 'required'],
            [['object_id', 'user_id', 'deleted', 'created_at', 'updated_at'], 'integer'],
            [['file', 'file_small'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 32],
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
            'file' => Yii::t('app', 'File'),
            'file_small' => Yii::t('app', 'File Small'),
            'type' => Yii::t('app', 'Type'),
            'object_id' => Yii::t('app', 'Object ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'deleted' => Yii::t('app', 'Deleted'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser($modelUser = null)
    {
        return $modelUser::findOne($this->user_id);
    }
}
