<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "net_ru".
 *
 * @property integer $city_id
 * @property integer $begin_ip
 * @property integer $end_ip
 */
class NetRu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'net_ru';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'begin_ip', 'end_ip'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'begin_ip' => 'Begin Ip',
            'end_ip' => 'End Ip',
        ];
    }
}
