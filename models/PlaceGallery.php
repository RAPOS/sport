<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "place_gallery".
 *
 * @property integer $id
 * @property string $image
 * @property integer $place_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Place $place
 */
class PlaceGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id', 'created_at', 'updated_at'], 'required'],
            [['place_id', 'created_at', 'updated_at'], 'integer'],
            [['image'], 'string', 'max' => 255],
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
            'image' => Yii::t('app', 'Image'),
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
