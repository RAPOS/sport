<?php

namespace app\models;

use Yii;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends User
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()):
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)):
                $this->addError($attribute, \Yii::t('app', 'Неверный логин или пароль.'));
            endif;
        endif;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'email'),
            'password' => Yii::t('app', 'password'),
            'rememberMe' => Yii::t('app', 'remember_me'),
        ];
    }

    public function login()
    {
        /* @var $user User */
        if ($this->validate()) {
            $this->status = ($user = $this->getUser()) ? $user->status : null;
            if ($this->status === User::STATUS_ACTIVE) {
                return \Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            } elseif ($this->status === User::STATUS_BLOCKED) {
                \Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'danger',
                        'icon' => 'fa fa-ban',
                        'message' => \Yii::t('app', 'Пользователь {email} заблокирован!', ['email' => '<strong>' . $this->email . '</strong>']),
                    ]
                );
            } elseif ($this->status === User::STATUS_WAIT) {
                \Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'warning',
                        'icon' => 'fa fa-info',
                        'message' => \Yii::t('app', 'Пользователь {email} не активирован!', ['email' => '<strong>' . $this->email . '</strong>']),
                    ]
                );
            }
            return false;
        }
        \Yii::$app->session->set(
            'message',
            [
                'type' => 'warning',
                'message' => \Yii::t('app', 'Не верный email или пароль.'),
            ]
        );
        return false;
    }
}
