<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "net_country".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $code
 */
class NetCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'net_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'code' => 'Code',
        ];
    }

    public static function getListCountries()
    {
        $query = NetCountry::find()->select("name_ru, id");

        $countries = $query->asArray()->all();
        $countriesList = [];

        foreach ($countries as $one)
            $countriesList[$one['id']] = $one["name_ru"];

        return $countriesList;
    }
}
