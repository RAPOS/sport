<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.08.2016
 * Time: 16:31
 */
namespace app\models;

use Yii;

class UserForm extends User
{
    public $password;
    public $confirm_password;
    public $agreeTerm = true;

    public $day;
    public $month;
    public $year;

    public $city;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['agreeTerm', 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Необходимо подтвердить согласие с пользовательским соглашением.')],
            [['last_name', 'first_name', 'email', 'day', 'month', 'year', 'sex'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            ['phone', 'validatePhone'],
            [['country_id', 'city_id'], 'integer'],
            ['description', 'string'],
            [['email', 'city'], 'string', 'max' => 100],
            [['email'], 'email'],
            ['email', 'unique',
                'targetClass'   => User::className(),
                'message'       => \Yii::t('app', 'Этот емайл уже зарегистрирован.'),
                'on'            => 'create'
            ],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            [['day'], 'integer', 'min' => 1, 'max' => 31],
            ['month', 'validateMonth'],
            [['year'], 'integer', 'min' => 1950, 'max' => 2000],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'email'                 => Yii::t('app', 'email'),
            'b_date'                => Yii::t('app', 'Дата рождения'),
            'first_name'            => Yii::t('app', 'Имя'),
            'last_name'             => Yii::t('app', 'Фамилия'),
            'description'           => Yii::t('app', 'Кратко о себе'),
            'sex'                   => Yii::t('app', 'Пол'),
            'password'              => Yii::t('app', 'Пароль'),
            'confirm_password'      => Yii::t('app', 'Повторите пароль'),
            'day'                   => Yii::t('app', 'День'),
            'month'                 => Yii::t('app', 'Месяц'),
            'year'                  => Yii::t('app', 'Год'),
            'agreeTerm'             => Yii::t('app', 'Пользовательское соглашение'),
            'country_id'            => Yii::t('app', 'Страна'),
            'city'                  => Yii::t('app', 'Город'),
            'phone'                 => Yii::t('app', 'Телефон'),
        ];
    }

    public function validatePhone() {
        if ($this->phone != null) {
            $phone = str_replace(['\\', '_', '-', ' ', '(', ')'], '', $this->phone);
            $phoneDigitsCount = strlen($phone);
            $modelCountry = GeoCountry::findOne($this->country_id);
            if ($phoneDigitsCount != $modelCountry->phone_number_digits_code) {
                $this->addError('phone', Yii::t('app', 'Не верный номер телефона'));
            } else {
                $this->phone = $phone;
            }
        }
    }

    public function validateMonth() {
        $months = $this->getMonthList();
        $month = 0;
        foreach ($months as $key => $value) {
            if ($value == $this->month) {
                $month = $key;
                break;
            }
        }
        if ($month == 0) {
            $this->addError('month', 'Не правильно заполнен месяц.');
        }
    }

    public function getMonthList() {
        return [
            1 => 'январь',
            2 => 'февраль',
            3 => 'март',
            4 => 'апрель',
            5 => 'май',
            6 => 'июнь',
            7 => 'июль',
            8 => 'август',
            9 => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь',
        ];
    }

    public function setBDate() {
        $months = $this->getMonthList();
        $month = 0;
        foreach ($months as $key => $value) {
            if ($value == $this->month) {
                $month = $key;
                break;
            }
        }
        $this->b_date = strtotime($this->year.'/'.$month.'/'.$this->day);;
    }

    public function getMonth($month) {
        $month = intval($month);
        $months = $this->getMonthList();
        $monthName = '';
        foreach ($months as $key => $value) {
            if ($key == $month) {
                $monthName = $value;
                break;
            }
        }
        return $monthName;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->status       = self::STATUS_WAIT;
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateEmailConfirmToken();
            } elseif ($this->email != null && $this->email_status == null) {
                $this->generateEmailConfirmToken();
            }
            $this->setBDate();

            return true;
        }
        return false;
    }

    /**
     * Отправка письма согласно шаблону "confirmEmail"
     * после регистрации
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole('user');
        $model = AuthAssignment::findOne(['user_id' => $this->id, 'item_name' => $role->name]);
        if (!$model) {
            $auth->assign($role, $this->id);
        }
    }

    public function sendActivationEmail($model)
    {
        return \Yii::$app->mailer->compose('activationEmail', ['user' => $model])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::t('app', '{app_name} (отправлено роботом).', ['app_name' => \Yii::$app->name])])
            ->setTo($this->email)
            ->setSubject(\Yii::t('app', 'Активация для {app_name}.', ['app_name' => \Yii::$app->name]))
            ->send();
    }
}