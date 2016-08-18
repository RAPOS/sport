<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "geo_region".
 *
 * @property integer $id
 * @property string $iso
 * @property string $country
 * @property string $name_ru
 * @property string $name_en
 * @property string $timezone
 * @property string $okato
 *
 * @property GeoCountry $countryFk
 */
class GeoRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso', 'country', 'name_ru', 'name_en', 'timezone', 'okato'], 'required'],
            [['iso'], 'string', 'max' => 7],
            [['country'], 'string', 'max' => 2],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['timezone'], 'string', 'max' => 30],
            [['okato'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'iso' => Yii::t('app', 'Iso'),
            'country' => Yii::t('app', 'Country'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_en' => Yii::t('app', 'Name En'),
            'timezone' => Yii::t('app', 'Timezone'),
            'okato' => Yii::t('app', 'Okato'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryFk()
    {
        return $this->hasOne(GeoCountry::className(), ['iso2' => 'country']);
    }
}
