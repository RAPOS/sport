<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 07.08.2016
 * Time: 17:21
 */

namespace app\models;

use deka6pb\geocoder\Geocoder;
use deka6pb\geocoder\objects\YandexObject;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;

class PlaceForm extends Place
{
    // Статусы места
    const STATUS_WAIT = 0;      // заблокирован
    const STATUS_ACTIVE = 1;    // активен
    const STATUS_BLOCKED = 2;   // ожидает подтверждения

    // тип места
    const PLACE = 1;            // место пользователя
    const GYM   = 2;            // активен
    public $country_id;
    public $city_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'city_id', 'address'], 'required'],
            [['status', 'count_views', 'is_gym', 'city_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'address'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['city_name'], 'string'],
            ['address', 'validateAddress'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название места'),
            'address' => Yii::t('app', 'Адрес'),
            'status' => Yii::t('app', 'Статус'),
            'count_views' => Yii::t('app', 'Количество просмотров'),
            'is_gym' => Yii::t('app', 'Is Gym'),
            'city_id' => Yii::t('app', 'City ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Дата добавления'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
            'country_id'       => Yii::t('app', 'Страна'),
            'city_name'          => Yii::t('app', 'Город'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    public function validateAddress() {
        /* @var \deka6pb\geocoder\abstraction\CoderInterface $coder */
        $coder = Geocoder::build(\deka6pb\geocoder\Geocoder::TYPE_YANDEX);
        $model = GeoCity::findOne($this->city_id);
        if ($this->address != '') {
            /* @var $object YandexObject */
            $address = $model->region->countryFk->name_ru.', '.$model->region->name_ru.', '.$model->name_ru.', '.$this->address;
            $object = $coder::findOneByAddress($model->region->countryFk->name_ru.', '.$model->region->name_ru.', '.$model->name_ru.', '.$this->address);
            $data = $object->getData();
            if ($data['street'] == null || $data['house'] == null) {
                $this->addError('address', Yii::t('app', 'Адрес <strong>{address}</strong> не найдет.', ['address' => $address]));
            } else {
                $this->address = $data['street'].', '.$data['house'];
            }
        }
    }

    public function getStatusName($status = null)
    {
        $status = (empty($status)) ? $this->status : $status ;

        if ($status === self::STATUS_BLOCKED)
        {
            return \Yii::t('app', "Заблокировано");
        }
        elseif ($status === self::STATUS_WAIT)
        {
            return \Yii::t('app', "Проверяется");
        }
        else
        {
            return \Yii::t('app', "Активировано");
        }
    }

    public function getStatusClass($status = null)
    {
        $status = (empty($status)) ? $this->status : $status ;

        if ($status === self::STATUS_BLOCKED)
        {
            return 'box-danger';
        }
        elseif ($status === self::STATUS_WAIT)
        {
            return 'box-warning';
        }
        else
        {
            return 'box-primary';
        }
    }


    public function getTempPhotos()
    {
        return Photo::find()->where([
            'object_id' => '0',
            'type' => 'place',
            'deleted' => 0
        ])->all();
    }

    public function getPhotos()
    {
        return Photo::find()->where([
            'object_id' => $this->id,
            'type' => 'place',
            'deleted' => 0
        ])->all();
    }

    public function getCarouselPhotos()
    {
        $model = $this->getPhotos();

        if ($model) {
            $items = [];
            $i = 0;
            foreach ($model as $one) {
                /* @var $one Photo */
                $items[$i] = Html::img($one->file_small, ['style: margin: 0; width: 100%;']);
                $i++;
            }
            return $items;
        }
        return false;
    }

    public function getFullAddress()
    {
        return $this->city->region->countryFk->name_ru.', '.$this->city->region->name_ru.', '.$this->city->name_ru.', '.$this->address;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->status   = self::STATUS_WAIT;
            $this->is_gym   = Yii::$app->user->can('office') ? self::GYM : self::PLACE;
            $this->user_id  = Yii::$app->user->id;
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
        $photos = $this->getTempPhotos();
        if ($photos) {
            /* $var $phots Photo[] */
            Photo::updateAll(['object_id' => $this->id], ['object_id' => 0, 'user_id' => Yii::$app->user->id]);
        }
    }

    public function beforeDelete()
    {
        /* @var $this User */
        parent::beforeDelete();
        $photos = $this->getPhotos();
        if ($photos) {
            /* $var $phots Photo[] */
            $model = Photo::updateAll(['deleted' => 1], ['object_id' => $this->id, 'user_id' => Yii::$app->user->id]);
            if ($model) {
                return true;
            }
        }
        return false;
    }
}