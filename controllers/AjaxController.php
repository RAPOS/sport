<?php

namespace app\controllers;

use app\models\Comments;
use app\models\Complaints;
use app\models\Dialog;
use app\models\Event;
use app\models\EventType;
use app\models\GeoCity;
use app\models\GeoCountry;
use app\models\NetCity;
use app\models\Notification;
use app\models\Place;
use app\models\Preferences;
use app\models\Request;
use app\models\User;
use Yii;
use app\models\PlaceGalary;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class AjaxController extends Controller
{
    public $layout = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index','Getcities'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionSearchCity($q)
    {
        $country_id = Yii::$app->request->get()['id'];
        $modelCountry = GeoCountry::findOne($country_id);

        $model = GeoCity::find()
            ->joinWith('region')
            ->where(['like', 'geo_city.name_ru', $q])
            ->andWhere(['geo_region.country' => $modelCountry->iso2])
            ->all();

        $results = [];

        foreach ($model as $one) {
            // @var $one GeoCity
            $results[] = [
                'id'        => $one['id'],
                'city'      => $one->name_ru,
                'region'    => $one->region->name_ru,
            ];
        }

        echo Json::encode($results);
        /*$country_id = Yii::$app->request->get()['id'];
        $modelCountry = GeoCountry::findOne($country_id);
        //$term       = Yii::$app->request->get()['term'];

        if (Yii::$app->request->isAjax) {
            $results = [];
            $q = addslashes($q);
            if (is_numeric($q)) {
                // @var GeoCity $model
                $model = GeoCity::findOne(['id' => $q]);
                if ($model) {
                    // @var $model GeoCity
                    $results[] = [
                        'id' => $model['id'],
                        'value' => $model->name_ru . ' - ' . $model->region->name_ru,
                    ];
                }
            } else {
                foreach (GeoCity::find()
                             ->joinWith('region')
                             ->where(['like', 'geo_city.name_ru', $q])
                             ->andWhere(['geo_region.country' => $modelCountry->iso2])
                             ->all() as $model) {
                    // @var $model GeoCity
                    $results[] = [
                        'id' => $model['id'],
                        'value' => $model->name_ru . ' - ' . $model->region->name_ru,
                    ];
                }
            }

            echo Json::encode($results);
        }*/
    }

    public function actionGetcities()
    {
        $this->layout = false;
        $model = new Event();
        $cities = NetCity::getCitiesByCountry($_POST['country_id']);

        return $this->renderPartial('selectCity',['model' => $model, 'data' =>$cities ]);
    }

    public function actionGetplaces()
    {
        $this->layout = false;
        $palces = Place::getPlaceByCity($_POST['city_id']);

        return $this->renderPartial('places', ['data' =>$palces ]);
    }

    public function actionAddplace()
    {
        $place = new Place();
        $place->name = $_POST['name'];
        $place->adress = $_POST['street'];
        $place->city_id = $_POST['city_id'];
        $place->user_id = Yii::$app->user->identity->id;
        $place->save();

        echo json_encode([
            'id'=>$place->id,
            'name'=>$place->name,
            'street'=>$place->adress
        ]);
    }

    public function actionSendrequest()
    {
        $event = Yii::$app->request->post('event_id');
        if (empty($event) || Request::issetRequest($event)) {
            throw new BadRequestHttpException;
        }

        $request = new Request();
        $request->user_id = Yii::$app->user->id;
        $request->event_id = $event;
        $request->status = 0;
        Notification::newNotification(Event::getAuthorIdForEvent($event), 0, Yii::t('app', 'user') ." ". Yii::$app->user->identity->getUserLinkWithLogin() .' '. Yii::t('app', 'applied_to_your') ." <a href='/event/event?id=".$event."' target='_blank'>". Yii::t('app', 'event') ."</a>");
        $request->save();
    }

    public function actionRequestdo()
    {
        /**
         * @var $event \app\models\Event
         * @var $request \app\models\Request
         */

        $type = Yii::$app->request->post('type');
        $id_request = Yii::$app->request->post('request_id');

        if (empty($type) || empty($id_request)) {
            throw new BadRequestHttpException;
        }

        $request = Request::findOne($id_request);
        $event = Event::findOne($request->event_id);

        if ($request->status == 0 AND $event->free_count_place > 0) {
            if ($type == 'accept') {
                $request->accept();
            } else {
                $request->reject();
            }
        }
    }

    public function actionRemoveplace()
    {
        PlaceGalary::findOne($_POST['id'])->delete();
    }

    public function actionNewmessage()
    {
        Dialog::writeMessage($_POST['user_id'], $_POST['text']);
    }

    public function actionGetdialog()
    {
        $oponents = $_POST['user'];
        $messages = Dialog::find()->where('user_read IN (:opponnents, :uid) AND user_write IN (:opponnents, :uid)', [':opponnents'=>$oponents, ':uid' => Yii::$app->user->getId()])->all();
        echo $this->renderPartial('/notification/messages',['messages' => $messages]);
    }

    public function actionUpdatemessage()
    {
        $oponents = $_POST['user'];
        $messages = Dialog::find()->where('user_read IN (:opponnents, :uid) AND user_write IN (:opponnents, :uid)', [':opponnents'=>$oponents, ':uid' => Yii::$app->user->getId()])->all();
        if(count($messages) > $_POST['length'] )
            echo $this->renderPartial('/notification/messages',['messages' => $messages]);
        else
            echo 0;
    }

    public function actionGetbusytime() {
        $curentEvent = "";
        if($_POST['current_event'])
            $curentEvent = $_POST['current_event'];

        $events = Event::getAllEventsByDate($_POST['date'], $_POST['place'], $curentEvent);
        $place = Place::findOne($_POST['place']);

        echo $this->renderPartial('/event/schedule',['events' => $events, 'date' => $_POST['date'],'place' => $place]);
    }

    public function actionAddpreference(){
        $where = "";
        $user_preference = Preferences::find()->where('user_id = :uid AND type = 0', [':uid' => Yii::$app->user->getId()])->all();
        if($user_preference) {
            $ids = [];
            foreach ($user_preference as $one)
                $ids[] = $one->object_id;

            if(count($ids))
               $where = "id NOT IN (".implode(",",$ids).")";
        }

        $sports = EventType::find()->where($where)->all();

        echo $this->renderPartial("/ajax/list_sport_preference",['sports'=>$sports]);
    }

    public function actionSavesportpreference(){
        $sports = [];
        foreach ($_POST['preference_spotr'] as $one){
            Preferences::add(0,$one);
            $sport = EventType::find()->where("id = ".$one)->one();
            $sports[] = $sport->name;
        }

        echo json_encode($sports);
    }

    public function actionSaveplacepreference(){
        if (Preferences::find()->where("user_id = :uid AND object_id = :obj AND type = 1", [':uid' => Yii::$app->user->getId(), ':obj' => $_POST['place']])->one()) {
            echo json_encode(['error' => 1]);
            return false;
        }

        Preferences::add(1,$_POST['place']);

        $place = Place::find()->where("id = ".$_POST['place'])->one();

        return json_encode([
            'name' => $place->name,
            'country' => $place->netCity->netCountry->name_ru,
            'city' => $place->netCity->name_ru,
            'address' => $place->adress,
            'error' => 0
        ]);
    }

    public function actionChangesubscription(){
        $user = \app\models\User::find()->where("id = :uid",[':uid'=>Yii::$app->user->getId()])->one();
        $user->preference_subscribe = 0;
        if ($_POST['preference_subscribe'] == "true")
            $user->preference_subscribe = 1;
        $user->save(false, ['preference_subscribe']);
    }

    public function actionSendcomplain()
    {
        if ($_POST['type'] == 0) {
            $event = Event::find()->where("id = " . $_POST['id'])->one();
            $currentUser = \app\models\User::find()->where("id = " . Yii::$app->user->getId())->one();
            Notification::newNotification($event->user_id, 4, Yii::t('app', 'user') .' '. $currentUser->getUserLinkWithLogin() .' '. Yii::t('app', 'complained_to_your') ." <a href='/event/event?id=" . $event->id . "' target='_blank'>". Yii::t('app', 'event') ."</a><br>". Yii::t('app', 'cause') .": <b>". $_POST['text'] ."</b>");
            Complaints::complain($_POST['id'], 0, Yii::$app->user->getId(), $_POST['text']);
        }

        if ($_POST['type'] == 1) {
            $place = Place::find()->where("id = " . $_POST['id'])->one();
            $currentUser = \app\models\User::find()->where("id = " . Yii::$app->user->getId())->one();
            if ($place->user_id)
                Notification::newNotification($place->user_id, 4, Yii::t('app', 'user') .' '. $currentUser->getUserLinkWithLogin() .' '. Yii::t('app', 'complained_to_your') ." <a href='/event/place?id=" . $place->id . "' target='_blank'>". Yii::t('app', 'place') ."</a><br>". Yii::t('app', 'cause') .": <b>" . $_POST['text'] ."</b>");
            Complaints::complain($_POST['id'], 1, Yii::$app->user->getId(), $_POST['text']);
        }

        if ($_POST['type'] == 2) {
            $user =  \app\models\User::find("id = " . $_POST['id'])->one();
            $currentUser = \app\models\User::find()->where("id = " . Yii::$app->user->getId())->one();

            Notification::newNotification($user->id, 4, Yii::t('app', 'user') .' '. $currentUser->getUserLinkWithLogin() .' '. Yii::t('app', 'complained_to_you') ."<br>". Yii::t('app', 'cause') .": <b>". $_POST['text'] ."</b>");
            Complaints::complain($_POST['id'], 2, Yii::$app->user->getId(), $_POST['text']);
        }
    }

    public function actionGetgyminfo()
    {
        $place = Place::find()->where('id = :pid', [':pid' => $_POST['id']])->one();

        if ($place->is_gym == 1) {
            echo $this->renderPartial('/event/_gymInfo',['model' => $place]);
        }
    }

    public function actionGetLastReviewUser()
    {
        if (Yii::$app->request->isGet)
            return $this->redirect(['/profile/statistic']);

        $uid = Yii::$app->request->post('uid');
        $reviews = Comments::find()->where('object_id = :uid AND type IN (2, 3)', [':uid' => $uid])->limit(3)->all();

        return $this->renderPartial('/ajax/last_review_user', ['reviews' => $reviews]);
    }


    // Змінюємо рівень уведомлений в профелі.
    public function actionChangenotificationlevel()
    {
        $user = \app\models\User::find("id = " . Yii::$app->user->getId())->one();

        if ($_POST['type'] == "on") {
            if (!$user->notLevel OR !in_array($_POST['level'], $user->notLevel)) {
                if(!$user->notLevel)
                    $user->notLevel = [];

                array_push($user->notLevel, $_POST['level']);
               // $user->notification_level[] = $_POST['level'];
            }
        } else if (is_array($user->a) AND count($user->notLevel))
            array_diff($user->notLevel, [$_POST['level']] ) ;

        if(!$user->save(false))
        {
            $user->validate();
            print_r($user->getErrors());
            die;
        }

    }

}