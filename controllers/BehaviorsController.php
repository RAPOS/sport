<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 30.06.2015
 * Time: 5:48
 */

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\UserOnlineBehavior;

class BehaviorsController extends Controller {

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['login', 'user-signup', 'office-signup', 'index', 'about', 'activate-account', 'error'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['profile'],
                        'actions' => ['index', 'statistic', 'user-profile', 'office-profile', 'change-password', 'profile-redirect'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['place'],
                        'actions' => ['delete'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['place'],
                        'actions' => ['index', 'create', 'update', 'view', 'profile-redirect'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['event'],
                        'actions' => ['delete'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['event'],
                        'actions' => ['index', 'create', 'update', 'view', 'profile-redirect', 'set-schedule'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['profile'],
                        'actions' => ['setup', 'mailing-update', 'places'],
                        'roles' => ['user']
                    ],
                ]
            ],
            'UserOnlineBehavior' => [
                'class' => UserOnlineBehavior::className()
            ]
        ];
    }
}