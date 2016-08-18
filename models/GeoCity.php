<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "geo_city".
 *
 * @property integer $id
 * @property integer $region_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $lat
 * @property string $lon
 * @property string $okato
 *
 * @property Event[] $events
 * @property Place[] $places
 * @property User[] $users
 * @property GeoRegion $region
 */
class GeoCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'integer'],
            [['name_ru', 'name_en', 'lat', 'lon', 'okato'], 'required'],
            [['lat', 'lon'], 'number'],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['okato'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region_id' => Yii::t('app', 'Region ID'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_en' => Yii::t('app', 'Name En'),
            'lat' => Yii::t('app', 'Lat'),
            'lon' => Yii::t('app', 'Lon'),
            'okato' => Yii::t('app', 'Okato'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id' => 'region_id']);
    }
}
