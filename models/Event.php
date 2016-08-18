<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $type
 * @property string $price
 * @property integer $date_start
 * @property integer $date_end
 * @property integer $count_place
 * @property integer $free_count_place
 * @property string $description
 * @property integer $min_count_place
 * @property integer $max_count_place
 * @property integer $coach
 * @property integer $duration
 * @property string $constantly_day
 * @property string $constantly_time
 * @property integer $count_views
 * @property integer $recalculate_price
 * @property integer $status
 * @property integer $city_id
 * @property integer $event_type
 * @property integer $place_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property GeoCity $city
 * @property EventType $eventType
 * @property Place $place
 * @property User $user
 * @property Request[] $requests
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'date_start', 'date_end', 'count_place', 'free_count_place', 'min_count_place', 'max_count_place', 'coach', 'duration', 'count_views', 'recalculate_price', 'status', 'city_id', 'event_type', 'place_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['date_start', 'date_end', 'city_id', 'event_type', 'place_id'], 'required'],
            [['description'], 'string', 'max' => 500],
            [['constantly_day', 'constantly_time'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['event_type'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['event_type' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
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
            'price' => Yii::t('app', 'Price'),
            'date_start' => Yii::t('app', 'Date Start'),
            'date_end' => Yii::t('app', 'Date End'),
            'count_place' => Yii::t('app', 'Count Place'),
            'free_count_place' => Yii::t('app', 'Free Count Place'),
            'description' => Yii::t('app', 'Description'),
            'min_count_place' => Yii::t('app', 'Min Count Place'),
            'max_count_place' => Yii::t('app', 'Max Count Place'),
            'coach' => Yii::t('app', 'Coach'),
            'duration' => Yii::t('app', 'Duration'),
            'constantly_day' => Yii::t('app', 'Constantly Day'),
            'constantly_time' => Yii::t('app', 'Constantly Time'),
            'count_views' => Yii::t('app', 'Count Views'),
            'recalculate_price' => Yii::t('app', 'Recalculate Price'),
            'status' => Yii::t('app', 'Status'),
            'city_id' => Yii::t('app', 'City ID'),
            'event_type' => Yii::t('app', 'Event Type'),
            'place_id' => Yii::t('app', 'Place ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'event_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['event_id' => 'id']);
    }
}
