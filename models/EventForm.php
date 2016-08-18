<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 08.08.2016
 * Time: 12:24
 */

namespace app\models;

use deka6pb\geocoder\Geocoder;
use deka6pb\geocoder\objects\YandexObject;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class EventForm extends Event
{
    public $start_date;
    public $duration_periof;

    public $day_1;
    public $day_2;
    public $day_3;
    public $day_4;
    public $day_5;
    public $day_6;
    public $day_7;
    public $start_time_1;
    public $start_time_2;
    public $start_time_3;
    public $start_time_4;
    public $start_time_5;
    public $start_time_6;
    public $start_time_7;
    public $duration_periof_1;
    public $duration_periof_2;
    public $duration_periof_3;
    public $duration_periof_4;
    public $duration_periof_5;
    public $duration_periof_6;
    public $duration_periof_7;

    // Статусы места
    const STATUS_WAIT = 0;      // заблокирован
    const STATUS_ACTIVE = 1;    // активен
    const STATUS_BLOCKED = 2;   // ожидает подтверждения

    // тип места
    const PLACE = 1;            // место пользователя
    const GYM   = 2;            // активен

    // тип события
    const EVENT_CONTINUED   = 0;    // постоянное событие
    const EVENT_SINGLE = 1;         // разовое событие


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'date_start', 'date_end', 'count_place', 'free_count_place', 'min_count_place', 'max_count_place', 'coach', 'duration', 'count_views', 'recalculate_price', 'status', 'city_id', 'event_type', 'place_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['date_start', 'date_end', 'city_id', 'event_type', 'type', 'place_id'], 'required'],
            [['description', 'start_date'], 'string', 'max' => 500],
            [['constantly_day', 'constantly_time'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['event_type'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['event_type' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['day_1', 'day_2', 'day_3', 'day_4', 'day_5', 'day_6', 'day_7'], 'boolean'],
            [['start_time_1', 'start_time_2', 'start_time_3', 'start_time_4', 'start_time_5', 'start_time_6', 'start_time_7'], 'string'],
            [['duration_periof_1', 'duration_periof_2', 'duration_periof_3', 'duration_periof_4', 'duration_periof_5', 'duration_periof_6', 'duration_periof_7'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Событие'),
            'price' => Yii::t('app', 'Стоимость'),
            'date_start' => Yii::t('app', 'Date Start'),
            'date_end' => Yii::t('app', 'Date End'),
            'count_place' => Yii::t('app', 'Count Place'),
            'free_count_place' => Yii::t('app', 'Free Count Place'),
            'description' => Yii::t('app', 'Описание'),
            'min_count_place' => Yii::t('app', 'Min Count Place'),
            'max_count_place' => Yii::t('app', 'Max Count Place'),
            'coach' => Yii::t('app', 'Coach'),
            'duration' => Yii::t('app', 'Длительность'),
            'constantly_day' => Yii::t('app', 'Constantly Day'),
            'constantly_time' => Yii::t('app', 'Constantly Time'),
            'count_views' => Yii::t('app', 'Count Views'),
            'recalculate_price' => Yii::t('app', 'Recalculate Price'),
            'status' => Yii::t('app', 'Status'),
            'city_id' => Yii::t('app', 'City ID'),
            'event_type' => Yii::t('app', 'Тип события'),
            'place_id' => Yii::t('app', 'Place ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'start_date'    => Yii::t('app', 'Начало события'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    public function getTypeList()
    {
        return [
            self::EVENT_SINGLE => \Yii::t('app', 'Разовое событие'),
            self::EVENT_CONTINUED =>  \Yii::t('app', 'Постоянное событие'),
        ];
    }

    public function getEventTypeList()
    {
        return ArrayHelper::map(EventType::find()->all(),
            'id', 'name'
        );
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
    }

    public function beforeDelete()
    {
        /* @var $this User */
        parent::beforeDelete();
        return true;
    }
}