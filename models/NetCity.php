<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "net_city".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $region
 * @property string $postal_code
 * @property string $latitude
 * @property string $longitude
 *
 * @property NetCountry $netCountry
 */
class NetCity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'net_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['name_ru', 'name_en'], 'string', 'max' => 100],
            [['region'], 'string', 'max' => 2],
            [['postal_code', 'latitude', 'longitude'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'region' => 'Region',
            'postal_code' => 'Postal Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    public static function getListCities($country_id)
    {
        $query = NetCity::find()->select("name_ru, id")->where('country_id = :id',[':id'=>$country_id]);

        $countries = $query->asArray()->all();
        $countriesList = [];

        foreach ($countries as $one)
            $countriesList[$one['id']] = $one["name_ru"];

        return $countriesList;
    }

    public static function getCitiesByCountry($country_id)
    {
        $query = NetCity::find()->select("id, name_ru")->where('country_id = :id',[':id' => $country_id]);

        $city = $query->asArray()->all();
        $cityesArr = [];

        foreach ($city as $one)
            $cityesArr[$one['id']] = $one["name_ru"];

        return $cityesArr;
    }

    public function getCitiesIdByCountry($country_id)
    {
        $ids_country = array();
        $id_country = NetCity::find()
            ->select('id')
            ->where('country_id = :cid', [':cid' => $country_id])
            ->asArray()
            ->all();

        // получаем масив с id всех городов
        foreach ($id_country as $one)
            $ids_country[] = $one['id'];

        // записываем их в одну переменную
        return implode(',', $ids_country);
    }

    public function getNetCountry()
    {
        return $this->hasOne(NetCountry::className(), ['id' => 'country_id']);
    }

}
