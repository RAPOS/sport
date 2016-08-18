<?php

namespace app\models;

use phpnt\oAuth\models\UserOauthKey;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property integer $email_status
 * @property string $phone
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $avatar_label
 * @property integer $b_date
 * @property string $last_name
 * @property string $first_name
 * @property string $phisical_address
 * @property string $company_name
 * @property string $entity_address
 * @property string $description
 * @property integer $sex
 * @property integer $city_id
 * @property integer $country_id
 * @property string $id_address
 * @property integer $status
 * @property integer $preference_subscribe
 * @property string $identification_code
 * @property string $authKey
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Blog[] $blogs
 * @property Comments[] $comments
 * @property Complaints[] $complaints
 * @property Dialog[] $dialogs
 * @property Dialog[] $dialogs0
 * @property Event[] $events
 * @property Mailing $mailing
 * @property Notification[] $notifications
 * @property Photo[] $photos
 * @property Place[] $places
 * @property Preferences[] $preferences
 * @property Request[] $requests
 * @property GeoCity $city
 * @property GeoCountry $country
 * @property UserEventType[] $userEventTypes
 * @property UserOauthKey[] $userOauthKeys
 * @property UserOnline $userOnline
 * @property UserProfile $userProfile
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $avatar;
    // Статусы пользователя
    const STATUS_WAIT = 0;      // заблокирован
    const STATUS_ACTIVE = 1;    // активен
    const STATUS_BLOCKED = 2;   // ожидает подтверждения

    // Гендерные статусы
    const SEX_MALE = 1;     // мужчина
    const SEX_FEMALE = 2;   // женщина

    // Время действия токенов
    const EXPIRE = 3600;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_status', 'b_date', 'sex', 'city_id', 'country_id', 'status', 'preference_subscribe', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['email', 'password_hash', 'password_reset_token', 'email_confirm_token', 'phisical_address', 'entity_address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['avatar_label'], 'string', 'max' => 10],
            [['last_name', 'first_name', 'company_name', 'identification_code'], 'string', 'max' => 30],
            [['id_address'], 'string', 'max' => 15],
            [['authKey', 'avatar'], 'string'],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
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
            'city'                  => Yii::t('app', 'Город'),
            'city_name'             => Yii::t('app', 'Город'),
            'address'               => Yii::t('app', 'Адрес'),
            'agreeTerm'             => Yii::t('app', 'Пользовательское соглашение'),
            'avatar'    => Yii::t('app', 'Аватар'),
            'login' => Yii::t('app', 'Login'),
            'email_status' => Yii::t('app', 'Email Status'),
            'phone' => Yii::t('app', 'Phone'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email_confirm_token' => Yii::t('app', 'Email Confirm Token'),
            'description' => Yii::t('app', 'Description'),
            'city_id' => Yii::t('app', 'City ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'id_address' => Yii::t('app', 'Id Address'),
            'status' => Yii::t('app', 'Status'),
            'preference_subscribe' => Yii::t('app', 'Preference Subscribe'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'count_friends_vk' => Yii::t('app', 'Count Friends Vk'),
            'vkontakte_id' => Yii::t('app', 'Vkontakte ID'),
            'facebook_id' => Yii::t('app', 'Facebook ID'),
            'count_friends_fb' => Yii::t('app', 'Count Friends Fb'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),

        ];
    }

    /**
     * Автозаполнение полей создание и редактирование
     * профиля
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * Статусы пользователя
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_BLOCKED => \Yii::t('app', 'Заблокирован'),
            self::STATUS_ACTIVE => \Yii::t('app', 'Активен'),
            self::STATUS_WAIT =>  \Yii::t('app', 'Не активен'),
        ];
    }

    public function getCountriesList()
    {
        $countries = GeoCountry::find()
            ->where(['short_name' => 'Russia'])
            ->orWhere(['short_name' => 'Ukraine'])
            ->orWhere(['short_name' => 'Belarus'])
            ->orWhere(['short_name' => 'Kazakhstan'])
            ->orderBy('name_ru')
            ->asArray()
            ->all();
        $countriesList = ArrayHelper::map($countries,
            'id', 'name_ru'
            /*function($countries) {
                //return Yii::t('app', $countries['short_name']).' +'.str_replace(['\\'], '', $countries['calling_code']);
                return Yii::t('app', $countries['short_name']);
            }*/
        );

        return $countriesList;
    }

    public function getRegionsList($country)
    {
        $model = GeoCountry::findOne($country);
        $regions = false;
        if ($model) {
            $regions = GeoRegion::find()
                ->where(['country' => $model->iso2])
                ->orderBy('name_ru')
                ->asArray()
                ->all();
        }

        if ($regions) {
            return ArrayHelper::map($regions,
                'id', 'name_ru'
            );
        }
        return $regions;
    }

    public function getCitiesList($region)
    {
        $regions = GeoCity::find()
            ->where(['region_id' => $region])
            ->orderBy('name_ru')
            ->asArray()
            ->all();

        return ArrayHelper::map($regions,
            'id', 'name_ru'
        );
    }

    public function getCityName()
    {
        if ($this->city_id) {
            $model = GeoCity::findOne($this->city_id);
        } else {
            $model = GeoCity::findOne(Yii::$app->geoData->city);
        }
        return $model->name_ru;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(),
            [
                'object_id' => 'id',
                'type' => 'avatar_label',
            ])->andWhere(['deleted' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOnline()
    {
        return $this->hasOne(UserOnline::className(), ['user_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfilePhoto()
    {
        return $this->hasOne(Photo::className(),
            [
                'object_id' => 'id',
                'type' => 'avatar_label',
            ])->andWhere(['deleted' => 0]);
    }

    public function getPhoneCode($country)
    {
        $model = GeoCountry::findOne($country);
        return $model->calling_code;
    }

    public function getPhoneMask($country)
    {
        $model = GeoCountry::findOne($country);
        switch ($model->id) {
            case 185:
                return '(999) 999-9999';
                break;
            case 122:
                return '(999) 999-9999';
                break;
        }
        return '(99) 999-9999';
    }
    
    public function getStatusName($status = null)
    {
        $status = (empty($status)) ? $this->status : $status ;

        if ($status === self::STATUS_BLOCKED)
        {
            return \Yii::t('app', "Ban");
        }
        elseif ($status === self::STATUS_WAIT)
        {
            return \Yii::t('app', "Not activated");
        }
        else
        {
            return \Yii::t('app', "Activated");
        }
    }

    public static function getUsername()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        return ($user->first_name || $user->last_name) ? $user->first_name.' '.$user->last_name : $user->email;
    }

    /**
     * Гендерный список
     * @return array
     */
    public static function getSexArray()
    {
        return [
            self::SEX_MALE =>  \Yii::t('app', 'Мужской'),
            self::SEX_FEMALE => \Yii::t('app', 'Женский'),
        ];
    }

    public static function getDaysList()
    {
        $i = 0;
        $item = [];
        while ($i < 31) {
            $i++;
            $item[$i] = $i;
        }
        return $item;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEventTypes()
    {
        return $this->hasMany(UserEventType::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogs()
    {
        return $this->hasMany(Blog::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplaints()
    {
        return $this->hasMany(Complaints::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['user_read' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs0()
    {
        return $this->hasMany(Dialog::className(), ['user_write' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypes()
    {
        return $this->hasMany(EventType::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailing()
    {
        return $this->hasOne(Mailing::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferences()
    {
        return $this->hasMany(Preferences::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOauthKeys()
    {
        return $this->hasMany(UserOauthKey::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * Поиск пользователя по Id
     * @param int|string $id - ID
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }




    /**
     * Ключ авторизации
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * ID пользователя
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Проверка ключа авторизации
     * @param string $authKey - ключ авторизации
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Поиск по токену доступа (не поддерживается)
     * @param mixed $token - токен
     * @param null $type - тип
     * @return NotSupportedException
     * @throws NotSupportedException - Исключение "Не подерживается"
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(\Yii::t('app', 'Поиск по токену не поддерживается.'));
    }

    /**
     * Проверка правильности пароля
     * @param $password - пароль
     * @return bool
     */
    public function validatePassword($password)
    {
        if ($this->password_hash !== null) {
            return \Yii::$app->security->validatePassword($password, $this->password_hash);
        }
        return false;
    }

    /**
     * Генераия Хеша пароля
     * @param $password - пароль
     */
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Поиск по токену восстановления паролья
     * Работает и для неактивированных пользователей
     * @param $token - токен восстановления пароля
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Генерация случайного авторизационного ключа
     * для пользователя
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * Проверка токена восстановления пароля
     * согласно его давности, заданной константой EXPIRE
     * @param $token - токен восстановления пароля
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + self::EXPIRE >= time();
    }

    /**
     * Генерация случайного токена
     * восстановления пароля
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Очищение токена восстановления пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Проверка токена подтверждения Email
     * @param $email_confirm_token - токен подтверждения электронной почты
     * @return null|static
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::find()
            ->where(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT])
            ->orWhere(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * Генерация случайного токена
     * подтверждения электронной почты
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = \Yii::$app->security->generateRandomString();
    }

    /**
     * Очищение токена подтверждения почты
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    /**
     * Связь с Role моделью.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        // Users has_one Role via Role.user_id -> id
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public static function getRolesList()
    {
        $roles = [];

        foreach (AuthItem::getRoles() as $item_name)
        {
            /* @var $item_name AuthItem */
            $roles[$item_name->name] = $item_name->name;
        }

        return $roles;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if ($this->email != null) {
                    $this->email_status     = 1;
                }
            }
            if (!$this->city_id) {
                $this->city_id = Yii::$app->geoData->city;
            }
            if (!$this->country_id) {
                $this->country_id = Yii::$app->geoData->country;
            }
            return true;
        }
        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        /* @var $this User */
        parent::afterSave($insert, $changedAttributes);
        $model = ($model = UserProfile::findOne($this->id)) ? $model : new UserProfile();
        if (!$model->isNewRecord) {
            $this->link('userProfile', $model);
        }

        $model = ($model = UserOnline::findOne($this->id)) ? $model : new UserOnline();
        if ($model->isNewRecord) {
            $this->link('userOnline', $model);
        }

        $model = ($model = Mailing::findOne($this->id)) ? $model : new Mailing();
        if ($model->isNewRecord) {
            $this->link('mailing', $model);
        }

        if ($this->avatar != null) {
            $model              = new Photo();
            $model->file        = $this->avatar;
            $model->file_small  = $this->avatar;
            $model->type        = 'avatar';
            $model->object_id   = $this->id;
            $model->user_id     = $this->id;
            $model->save();
        }
        $auth = \Yii::$app->authManager;

        $role = $auth->getRole('user');
        $model = AuthAssignment::findOne(['user_id' => $this->id, 'item_name' => $role->name]);
        if (!$model) {
            $auth->assign($role, $this->id);
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
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
     * Действия, выполняющиеся после авторизации.
     * Сохранение IP адреса и даты авторизации.
     *
     * Для активации текущего обновления необходимо
     * повесить текущую функцию на событие 'on afterLogin'
     * компонента user в конфигурационном файле.
     * @param $id - ID пользователя
     */
    public static function afterLogin($id)
    {
        self::getDb()->createCommand()->update(self::tableName(), [
            'ip' => $_SERVER["REMOTE_ADDR"],
        ], ['id' => $id])->execute();
    }

    /**
     * Список всех пользователей
     * @param bool $show_id - показывать ID пользователя
     * @return array - [id => Имя Фамилия (ID)]
     */
    public static function getAll($show_id = false)
    {
        $users = [];
        $model = self::find()->all();
        if ($model) {
            foreach ($model as $m) {
                $name = ($m->last_name) ? $m->first_name . " " . $m->last_name : $m->first_name;
                if ($show_id) {
                    $name .= " (".$m->id.")";
                }
                $users[$m->id] = $name;
            }
        }

        return $users;
    }
}
