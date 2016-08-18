<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.08.2016
 * Time: 18:56
 */

namespace app\models;

use deka6pb\geocoder\Geocoder;
use deka6pb\geocoder\objects\YandexObject;
use Yii;

class OfficeForm extends User
{
    public $phone_num;
    public $confirm_password;
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
            ['agreeTerm', 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Необходимо подтвердить согласие с пользовательским соглашением.')],
            [['email', 'company_name', 'phone_num', 'country', 'city', 'city_id', 'address'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            ['city_id', 'integer'],
            ['address', 'validateAddress'],
            [['email', 'country', 'city'], 'string', 'max' => 100],
            [['email'], 'email'],
            ['email', 'unique',
                'targetClass'   => User::className(),
                'message'       => \Yii::t('app', 'Этот емайл уже зарегистрирован.'),
                'on'            => 'create'
            ],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'email'                 => Yii::t('app', 'email'),
            'password'         => Yii::t('app', 'Пароль'),
            'confirm_password'      => Yii::t('app', 'Повторите пароль'),
            'agreeTerm'             => Yii::t('app', 'Пользовательское соглашение'),
            'country'               => Yii::t('app', 'Страна'),
            'city'                  => Yii::t('app', 'Город'),
            'address'               => Yii::t('app', 'Адрес'),
            'company_name'          => Yii::t('app', 'Название организации'),
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
        $model = GeoCity::findOne($this->city_id);
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

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->status       = self::STATUS_WAIT;
            }
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
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole('office');
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