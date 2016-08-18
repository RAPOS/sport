<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "net_country_ip".
 *
 * @property integer $country_id
 * @property integer $begin_ip
 * @property integer $end_ip
 */
class NetCountryIp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'net_country_ip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'begin_ip', 'end_ip'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'begin_ip' => 'Begin Ip',
            'end_ip' => 'End Ip',
        ];
    }
}
