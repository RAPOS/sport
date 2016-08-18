<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\BaseController;

class PageController extends BaseController
{
    public $layout = 'user_login';

    public function beforeAction($event)
    {
        if(Yii::$app->user->isGuest)
            $this->layout = "main";
        else
            $this->layout = "user_login";

        return parent::beforeAction($event);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','howitwork'],
                'rules' => [
                    [
                        'actions' => ['index','howitwork'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function actionHowitwork()
    {
        return $this->render('howItWork');
    }

    public function actionRules()
    {
        return $this->render('rules');
    }

    public function actionIndex()
    {
        print_r(1); die;
    }
}