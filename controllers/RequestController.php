<?php

namespace app\controllers;

use app\models\Comments;
use app\models\Event;
use app\models\Request;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\components\BaseController;

class RequestController extends BaseController
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
                    ]
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

    public function actionEvent()
    {
        /** @var Event $event */

        $id_event = Yii::$app->request->get('id');

        if (empty($id_event)) {
            throw new BadRequestHttpException;
        }

        $event = Event::findOne($id_event);

        if ($event->user_id != Yii::$app->user->id) {
            return $this->goBack();
        }

        $comment = new Comments();

        if (Yii::$app->request->isAjax && $comment->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($comment);
        }

        if ($comment->load(Yii::$app->request->post())) {
            $newComment = new Comments();
            $newComment->newComment($comment->object_id, 3, $comment->comment, $comment->rating);
            $this->redirect("/event/myevents");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Request::find()->where(['event_id' => $id_event])->orderBy('status'),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('event', [
            'event' => $event,
            'comment' => $comment,
            'dataProvider' => $dataProvider
        ]);
    }

}