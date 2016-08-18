<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AdminPlaceFilterForm extends Model
{
    public $city;
    public $country;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['city', 'country'], 'integer'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */

    public function attributeLabels()
    {
        return [
            'city' => Yii::t('app', 'town'),
            'country' => Yii::t('app', 'county'),
            'status' => Yii::t('app', 'status'),
        ];
    }

}
