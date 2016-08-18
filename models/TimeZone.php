<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bla_time_zone".
 *
 * @property integer $id
 * @property string $name
 * @property integer $time
 */
class TimeZone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bla_time_zone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'time'], 'required'],
            [['time'], 'integer'],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'time' => 'Time',
        ];
    }
}
