<?php

namespace app\models;

use deka6pb\geocoder\Geocoder;
use deka6pb\geocoder\objects\YandexObject;
use Yii;

/**
 * LoginForm is the model behind the login form.
 */
class SignupForm extends User
{
    public $phoneNum;
    public $password;
    public $confirm_password;
    public $day;
    public $month;
    public $year;
    public $agreeTerm = true;
    public $country;
    public $city;
    public $address;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'email', 'type', 'agreeTerm'], 'required'],
            ['agreeTerm', 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Необходимо подтвердить согласие с пользовательским соглашением.')],
            [['last_name', 'first_name', 'day', 'month', 'year', 'sex'], 'required', 'on' => 'default'],
            [['company_name', 'phoneNum', 'country', 'city_name', 'city', 'address'], 'required', 'on' => 'entity'],
            ['phoneNum', 'validatePhone', 'on' => 'entity'],
            ['address', 'validateAddress', 'on' => 'entity'],
            ['month', 'validateMonth', 'on' => 'default'],
            [['email', 'address', 'city_name'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['id'], 'number'],
            [['password'], 'string', 'max' => 300],
            [['password'], 'string', 'min' => 6],
            ['country', 'string'],
            [['city'], 'integer'],
            [['day'], 'integer', 'min' => 1, 'max' => 31],
            [['year'], 'integer', 'min' => 1950, 'max' => 2000],
            ['email', 'unique',
                'targetClass' => User::className(),
                'message' => \Yii::t('app', 'Этот емайл уже зарегистрирован.')],
            ['confirm_password', 'compare', 'compareAttribute'=>'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'type'                  => Yii::t('app', 'your'),
            'email'                 => Yii::t('app', 'email'),
            'password'              => Yii::t('app', 'password'),
            'b_date'                => Yii::t('app', 'date_of_birth'),
            'last_name'             => Yii::t('app', 'surname'),
            'first_name'            => Yii::t('app', 'name'),
            'sex'                   => Yii::t('app', 'sex'),
            'date_reg'              => Yii::t('app', 'date_of_registration'),
            'reCaptcha'             => "Вы робот?",
            'company_name'          => Yii::t('app', 'company_name'),
            'identification_code'   => Yii::t('app', 'identification_code'),
            'phoneNum'                 => Yii::t('app', 'phone'),
            'entity_address'        => Yii::t('app', 'entity_address'),
            'phisical_address'      => Yii::t('app', 'physical_address'),
            'confirm_password'      => Yii::t('app', 'Повторите пароль'),
            'day'                   => Yii::t('app', 'День'),
            'month'                 => Yii::t('app', 'Месяц'),
            'year'                  => Yii::t('app', 'Год'),
            'country'               => Yii::t('app', 'Страна'),
            'city'                  => Yii::t('app', 'Город'),
            'city_name'             => Yii::t('app', 'Город'),
            'address'               => Yii::t('app', 'Адрес'),
            'agreeTerm'             => Yii::t('app', 'Пользовательское соглашение'),
        ];
    }

    public function validatePhone() {
        $phone = str_replace(['\\', '_', '-', ' ', '(', ')'], '', $this->phoneNum);
        $phoneDigitsCount = strlen($phone);
        $modelCountry = GeoCountry::findOne($this->country);
        if ($phoneDigitsCount != $modelCountry->phone_number_digits_code) {
            $this->addError('phone', Yii::t('app', 'Не верный номер телефона'));
        } else {
            $this->phone = $phone;
        }
    }

    public function validateAddress() {
        /* @var \deka6pb\geocoder\abstraction\CoderInterface $coder */
        $coder = Geocoder::build(\deka6pb\geocoder\Geocoder::TYPE_YANDEX);
        $model = GeoCity::findOne($this->city);
        //dd([$model->region->countryFk->name_ru, $model->region->name_ru, $model->name_ru, $this->address]);
        if ($this->address != '') {
            /* @var $object YandexObject */
            $address = $model->region->countryFk->name_ru.', '.$model->region->name_ru.', '.$model->name_ru.', '.$this->address;
            $object = $coder::findOneByAddress($model->region->countryFk->name_ru.', '.$model->region->name_ru.', '.$model->name_ru.', '.$this->address);
            /* [
                'city' => 'Нижний Новгород'
                'area' => 'Нижегородская область'
                'sub_area' => 'городской округ Нижний Новгород'
                'dependent_locality' => null
                'country' => 'Россия'
                'countrySlug' => null
                'thoroughfare' => 'проспект Ленина'
                'street' => 'проспект Ленина'
                'house' => '5'
            ] */
            $data = $object->getData();

            if ($data['street'] == null || $data['house'] == null) {
                $this->addError('address', Yii::t('app', 'Адрес <strong>{address}</strong> не найдет.', ['address' => $address]));
            } else {
                $this->address = $data['street'].', '.$data['house'];
                $this->entity_address = $data['street'].', '.$data['house'];
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
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
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

    /**
     * Генерация ключа авторизации, токена подтверждения регистрации
     * и хеширование пароля перед сохранением
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->status       = self::STATUS_WAIT;
            $this->city_id      = $this->city;
            $this->country_id   = $this->country;
            $this->setBDate();
            $this->setPassword($this->password);
            $this->generateAuthKey();
            $this->generateEmailConfirmToken();
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
        //dd([$insert, $changedAttributes, $this]);
        $model = new UserProfile();
        $this->link('userProfile', $model);
        $auth = \Yii::$app->authManager;
        if ($this->type == '1') {
            $authorRole = $auth->getRole('office');
        } else {
            $authorRole = $auth->getRole('user');
        }
        $auth->assign($authorRole, $this->id);
        //dd(555);
        /*Yii::$app->mailer->compose($view, ['model' => $this])
            ->setFrom([Yii::$app->params['adminEmail']])
            ->setTo($this->email)
            ->setSubject(Yii::t('user', 'Подтверждение регистрации на сайте'))
            ->send();*/
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
