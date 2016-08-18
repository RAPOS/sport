<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bla_place_galary".
 *
 * @property integer $id
 * @property integer $place_id
 * @property string $image
 */
class PlaceGalary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bla_place_galary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id', 'image'], 'required'],
            [['place_id'], 'integer'],
            [['image'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => Yii::t('app', 'place'),
            'image' => Yii::t('app', 'image'),
        ];
    }
}
