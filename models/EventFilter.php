<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class EventFilter extends Model
{
    public $type;
    public $region;
    public $country;
    public $city;
    public $place;
    public $date_start;
    public $date_end;
    public $withCouch;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'event_type'),
            'country' => Yii::t('app', 'country'),
            'withCouch' => Yii::t('app', 'only_trainer'),
            'city' => Yii::t('app', 'town'),
            'place' => Yii::t('app', 'place'),
            'date_start' => Yii::t('app', 'from'),
            'date_end' => Yii::t('app', 'before'),
        ];
    }

}
