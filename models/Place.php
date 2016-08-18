<?php

namespace app\models;

use Yii;
use yii\bootstrap\Html;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $status
 * @property integer $count_views
 * @property integer $is_gym
 * @property integer $city_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Event[] $events
 * @property GymInfo[] $gymInfos
 * @property GeoCity $city
 * @property User $user
 * @property PlaceGallery[] $placeGalleries
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'city_id'], 'required'],
            [['status', 'count_views', 'is_gym', 'city_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'address'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'count_views' => Yii::t('app', 'Count Views'),
            'is_gym' => Yii::t('app', 'Is Gym'),
            'city_id' => Yii::t('app', 'City ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['place_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGymInfos()
    {
        return $this->hasMany(GymInfo::className(), ['place_id' => 'id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceGalleries()
    {
        return $this->hasMany(PlaceGallery::className(), ['place_id' => 'id']);
    }
}
