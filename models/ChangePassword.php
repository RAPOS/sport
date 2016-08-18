<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.08.2016
 * Time: 23:35
 */

namespace app\models;

use Yii;

class ChangePassword extends User
{
    public $password;
    public $confirm_password;
    public $password_old;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'password_old'], 'required'],
            [['password', 'password_old'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'              => Yii::t('app', 'Пароль'),
            'confirm_password'      => Yii::t('app', 'Повторите пароль'),
            'password_old'          => Yii::t('app', 'Старый пароль'),
        ];
    }

    public function validateOldPassword()
    {
        if (!$this->hasErrors()):
            if (!$this || !$this->validatePassword($this->password_old)):
                return false;
            endif;
        endif;
        return true;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /* @var $user User */
            if ($this->validateOldPassword()) {
                $this->setPassword($this->password);
                return true;
            }
        }
        return false;
    }
}