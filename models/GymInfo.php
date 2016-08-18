<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gym_info".
 *
 * @property integer $id
 * @property string $schedule
 * @property integer $price_per_hour
 * @property integer $place_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Place $place
 */
class GymInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gym_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_per_hour', 'place_id', 'created_at', 'updated_at'], 'integer'],
            [['place_id', 'created_at', 'updated_at'], 'required'],
            [['schedule'], 'string', 'max' => 500],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'schedule' => Yii::t('app', 'Schedule'),
            'price_per_hour' => Yii::t('app', 'Price Per Hour'),
            'place_id' => Yii::t('app', 'Place ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
}
