<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\AddressProfile;
use app\models\ChangePassForm;
use app\models\Comments;
use app\models\Dialog;
use app\models\Notification;
use app\models\User;
use app\models\UserProfile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;

class NotificationController extends BaseController
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
                        'actions' => ['show'],
                        'allow' => true,
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

    public function actionIndex()
    {
        $user = User::find()->where("id = :uid",[':uid'=>Yii::$app->user->getId()])->one();

        $dialogs  = Dialog::find()->where('user_read = :uid OR user_write = :uid', [':uid' => Yii::$app->user->getId()])->groupBy('user_read, user_write')->orderBy('date DESC')->all();

        if (empty($dialogs))
            return $this->render('noMessages');

        $oponents = $dialogs[0]->userOpponents()->id;
        $messages = Dialog::find()->where('user_read IN (:opponnents, :uid) AND user_write IN (:opponnents, :uid)', [':opponnents'=>$oponents, ':uid' => Yii::$app->user->getId()])->all();

        return $this->render('index', [
            'dialogs' => $dialogs,
            'messages' => $messages,
            'user' => $user
        ]);
    }

    public function actionList()
    {
        Notification::updateAll(['status' => 1], ['=', 'user_id', Yii::$app->user->getId()]);
        $notification = Notification::find()->where('user_id = :uid', [':uid' => Yii::$app->user->getId()])->orderBy("id DESC")->all();
        return $this->render('notification_list', ['notification' => $notification]);
    }

}