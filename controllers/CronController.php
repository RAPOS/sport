<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Dialog;
use app\models\Event;
use app\models\Notification;
use app\models\Request;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\filters\VerbFilter;

class CronController extends BaseController
{
    public $layout = 'user_login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['cron1', 'cron2'],
                        'roles' => ['?'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /*
     * Нагадування про найблищі події
     * todo Запустити з періодом 3 год
     */
    public function actionCron1()
    {
        $date1 = date("Y-m-d H:i:s", time() - (60*60*3));
        $date2 = date("Y-m-d H:i:s", time() - (60*60*2.5));

        $events = Event::find()->where('date_start BETWEEN :date1 AND :date2',[':date1' => $date1, ':date2' => $date2])->all();

        if ($events)
            foreach ($events as $one) {
                $request = Request::find()->where('event_id = :eventId AND status = 1', [':eventId' => $one->id])->all();
                if ($request)
                    foreach ($request as $req)
                        Notification::newNotification($request->user_id,3,"Уважаемый ".$req->user->login.", напоминаем Вам, что уже севодня о ".$req->event->date_start.", у Вас запланированное <a href='/event/event?id=".$req->event->id."' target='_blank'>событие</a>");
            }
    }

    /*
     * Розсилка найблищих подій відносно вподобань.
     * todo Запустити з періодом 1 день в 01:00
     */
    public function actionCron2()
    {
        $users = User::find()->all();
        foreach ($users as $one) {
            $listSportPreferences = $one->getAllPreferences(0);
            $listPlacePreferences = $one->getAllPreferences(1);
            $sports = $place = "";
            if (!count($listSportPreferences) AND !count($listPlacePreferences))
                continue;

            if ($listSportPreferences)
                $sports = " AND event_type IN (".implode(",",$listSportPreferences).")";

            if ($listPlacePreferences)
                $place = " AND place_id IN (".implode(",",$listSportPreferences).")";

            $events = Event::find()->where("user_id <> :uid AND date_start LIKE '%".date("Y-m-d")."%' AND free_count_place > 0 AND (1 ".$sports.$place.")", [':uid' => Yii::$app->user->getId()])->all();
            
            $norification = Yii::t('app', 'notification_according_to_you_preferences');

            if($events) {
                foreach ($events as $oneEvent)
                    $norification .= '<p>О ' . date("H:i:s", strtotime($oneEvent->date_start)) . ' всего за <strong>' . $oneEvent->getPrice() . '</strong> можно пойти еще с <em>' . $oneEvent->free_count_place . ' чел.</em> на ' . $oneEvent->eventType->name . ' в "' . $oneEvent->place->name . '", что по адресу: ' . $oneEvent->netCity->netCountry->name_ru . ' г. ' . $oneEvent->netCity->name_ru . ', ул. ' . $oneEvent->place->adress . ' </p>';

                Notification::newNotification($one->id, 5, $norification);
            }
        }
    }

    /**
     * Нагадуємо власнику та учасникам події, про те що вони не залишили відгуки.
     * todo Запустити з періодом 03:00 години
     */
    public function actionCron3()
    {
        // Нагадуємо власнику події, про те що він не залишив відгук про учасників.
        $requests = Request::find()
            ->joinWith('event')
            ->where("bla_request.status = 1 AND comment_for_participant = 0 AND bla_event.date_end < DATE_SUB(now(), INTERVAL 2 DAY)")
            ->groupBy('bla_event.id, bla_request.user_id')
            ->all();

        if ($requests) {
            foreach ($requests as $one) {
                $one->comment_for_participant = 1;
                $one->save(false);
                Notification::newNotification($one->event->user_id, 7, Yii::t('app', 'leave_review_on_participant') ." <a href='".Url::to(['/profile/user/?id='.$one->user_id], true)."'>".$one->user->last_name ." ".$one->user->first_name."</a>");
            }
        }

        // Нагадуємо учасникам події, про те що вони не залишили відгук про вланика.
        $requests = Request::find()
            ->joinWith('event')
            ->where("bla_request.status = 1 AND comment_for_owner = 0 AND bla_event.date_end < DATE_SUB(now(), INTERVAL 2 DAY)")
            ->all();

        if ($requests) {
            foreach ($requests as $one) {
                $one->comment_for_owner = 1;
                $one->save(false);
                Notification::newNotification($one->user_id, 7, Yii::t('app', 'leave_review_on_author') ." <a href='".Url::to(['/profile/user/?id='.$one->event->user_id], true)."'>".$one->event->user->last_name ." ".$one->event->user->first_name."</a>");
            }
        }
    }

    /**
     * Нагадуємо користувачу, що у нього є приватне повідомлення
     * todo Запустити з періодом 5 хвилин
     */
    public function actionCron4()
    {
        $minutes = \Yii::$app->params['dialog_notification_period_minutes'];
        $dialog = Dialog::find()
            ->where("is_read = 0 AND reminder_notification = 0 AND date < DATE_SUB(now(), INTERVAL 30 MINUTE)", [':minutes' => $minutes])
            ->all();

        foreach ($dialog as $one) {
            Notification::newNotification($one->user_read, 8, "Пользователь ".$one->writer->last_name." ".$one->writer->first_name." оставил Вам личное сообщение.");
            $one->reminder_notification = 1;
            $one->save();
        }
    }

}