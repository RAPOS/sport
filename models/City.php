<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rCity".
 *
 * @property integer $ID
 * @property integer $Region
 * @property integer $District
 * @property integer $Country
 * @property string $Prefix
 * @property string $Name
 * @property string $TZ
 * @property string $TimeZone
 * @property string $TimeZone2
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rCity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Region', 'District', 'Country'], 'integer'],
            [['Name'], 'required'],
            [['Prefix'], 'string', 'max' => 50],
            [['Name', 'TZ'], 'string', 'max' => 128],
            [['TimeZone', 'TimeZone2'], 'string', 'max' => 100],
            [['Country', 'Region', 'Name'], 'unique', 'targetAttribute' => ['Country', 'Region', 'Name'], 'message' => 'The combination of Region, Country and Name has already been taken.'],
            [['Prefix'], 'unique']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['ID' => 'Region']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Region' => 'Region',
            'District' => 'District',
            'Country' => 'Country',
            'Prefix' => 'Prefix',
            'Name' => 'Name',
            'TZ' => 'Tz',
            'TimeZone' => 'Time Zone',
            'TimeZone2' => 'Time Zone2',
        ];
    }

    public static function getCityesByRegion($region_id){
        $query = City::find()->select("ID, Name")->where('Region = :r',[':r' => $region_id]);

        $city = $query->asArray()->all();
        $cityesArr = [];

        foreach ($city as $one)
            $cityesArr[$one['ID']] = $one["Name"];

        return $cityesArr;
    }

    public static function getListCities()
    {
        $query = City::find()->select("Name, ID");

        $cities = $query->asArray()->all();
        $citiesList = [];

        foreach ($cities as $one)
            $citiesList[$one['ID']] = $one["Name"];

        return $citiesList;
    }
}
