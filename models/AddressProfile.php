<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AddressProfile extends Model
{
    public $street;
    public $house_namber;
    public $city;
    public $country = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
           // [['email', 'password'], 'required'],
           // ['rememberMe', 'boolean'],
           // ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = User::find()
                ->where(['email' => $_POST['LoginForm']['email'], 'password' => md5($_POST['LoginForm']['password'])])->one();

            if (!$user)
                $this->addError($attribute, Yii::t('app', 'error_login_or_password'));
        }
    }

    public function attributeLabels()
    {
        return [
            'street' => Yii::t('app', 'street'),
            'house_namber' => Yii::t('app', 'house'),
            'city' => Yii::t('app', 'town'),
            'country' => Yii::t('app', 'country'),
        ];
    }



}
