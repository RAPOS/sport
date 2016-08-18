<?php

namespace app\controllers;

use app\models\ChangePassword;
use app\models\Comments;
use app\models\MailingForm;
use app\models\Notification;
use app\models\Request;
use app\models\User;
use app\models\UserForm;
use app\models\UserProfile;
use app\models\Dialog;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ProfileController extends BehaviorsController
{
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

    public function actionPlaces()
    {
        //$modelMailingForm = MailingForm::findOne(Yii::$app->user->id);
        return $this->render('places', [
            //'modelMailingForm'         => $modelMailingForm,
        ]);
    }

    public function actionSetup()
    {
        $modelMailingForm = MailingForm::findOne(Yii::$app->user->id);
        if ($modelMailingForm->user->email_status == null) {
            return $this->redirect('/profile/profile-redirect');
        }

        return $this->render('setup', [
            'modelMailingForm'         => $modelMailingForm,
        ]);
    }

    public function actionMailingUpdate()
    {
        $model = MailingForm::findOne(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                \Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'warning',
                        'message' => \Yii::t('app', 'Параметры рассылки успешно изменены.'),
                    ]
                );
        }

        return $this->render('_mailing-form', [
            'model'         => $model
        ]);
    }

    public function actionUserProfile()
    {
        $model = UserForm::findOne(Yii::$app->user->id);
        $modelPassword = new ChangePassword();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (($model->email != null && $model->email_status == null) && $model->sendActivationEmail($model)) {
                \Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'success',
                        'message' => \Yii::t('app', 'Письмо с активацией отправленно на <strong> {email} </strong> (проверьте папку спам).', ['email' => $model->email]),
                    ]
                );
            } else {
                \Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'success',
                        'message' => \Yii::t('app', 'Профиль успешно изменен.'),
                    ]
                );
            }

        }

        if ($model->b_date) {
            $model->day = intval(Yii::$app->formatter->asDate($model->b_date, 'php:d'));
            $model->month = $model->getMonth(Yii::$app->formatter->asDate($model->b_date, 'php:m'));
            $model->year = intval(Yii::$app->formatter->asDate($model->b_date, 'php:Y'));
        }

        $model->validate();

        return $this->render('index', [
            'model'         => $model,
            'modelPassword' => $modelPassword
        ]);
    }

    public function actionChangePassword()
    {
        $model = ChangePassword::findOne(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'message' => \Yii::t('app', 'Пароль успешно изменен.'),
                ]
            );
        } else {
            \Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'message' => \Yii::t('app', 'Пароль не изменен. Старый пароль введен не верно.'),
                ]
            );
        }

        if (Yii::$app->user->can('office')) {
            return $this->redirect('/profile/office-profile');
        } else {
            return $this->redirect('/profile/user-profile');
        }
    }


    public function actionIndex()
    {
        if (Yii::$app->user->can('office')) {
            return $this->redirect('/profile/office-profile');
        } else {
            return $this->redirect('/profile/user-profile');
        }
    }

    public function actionProfileRedirect()
    {
        \Yii::$app->session->set(
            'message',
            [
                'type' => 'danger',
                'message' => \Yii::t('app', 'Подтвердите ваш емайл.'),
            ]
        );
        if (Yii::$app->user->can('office')) {
            return $this->redirect('/profile/office-profile');
        }

        return $this->redirect('/profile/user-profile');
    }
}