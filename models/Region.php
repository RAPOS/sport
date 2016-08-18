<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rRegion".
 *
 * @property integer $ID
 * @property integer $Country
 * @property integer $District
 * @property string $Name
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rRegion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Country', 'Name'], 'required'],
            [['Country', 'District'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['Country', 'Name'], 'unique', 'targetAttribute' => ['Country', 'Name'], 'message' => 'The combination of Country and Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Country' => Yii::t('app', 'country'),
            'District' => 'District',
            'Name' => Yii::t('app', 'name'),
        ];
    }

    public static function getListRegions()
    {
        $query = Region::find()->select("Name, ID");

        $region = $query->asArray()->all();
        $regions = [];

        foreach ($region as $one)
            $regions[$one['ID']] = $one["Name"];

        return $regions;
    }

}
