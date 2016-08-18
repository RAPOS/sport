<?php

namespace app\controllers;

use app\models\AccountActivation;
use app\models\OfficeForm;
use app\models\User;
use app\models\UserForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\components\BaseController;

class SiteController extends BehaviorsController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute(['/profile/index']));
        }


        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/profile/index');
        }

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->login()) {
                return $this->redirect('/profile/index');
            }
            $model->password = '';
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionUserSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $model = new UserForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->status === User::STATUS_ACTIVE) {
                if (\Yii::$app->getUser()->login($model)) {
                    return $this->redirect('/profile/index');
                }
            } else {
                if ($model->sendActivationEmail($model)) {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'success',
                            'icon' => 'glyphicon glyphicon-envelope',
                            'message' => \Yii::t('app', 'Письмо с активацией отправленно на <strong> {email} </strong> (проверьте папку спам).', ['email' => $model->email]),
                        ]
                    );
                    return $this->redirect(Url::to(['/site/index']));
                } else {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'icon' => 'glyphicon glyphicon-envelope',
                            'message' => \Yii::t('app', 'Ошибка. Письмо не отправлено.'),
                        ]
                    );
                    \Yii::error(\Yii::t('app', 'Error. The letter was not sent.'));
                }
                return $this->refresh();
            }
            return $this->redirect('/profile/index');
        }
        return $this->render('user-signup', ['model' => $model]);
    }

    public function actionOfficeSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }

        $model = new OfficeForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->status === User::STATUS_ACTIVE) {
                if (\Yii::$app->getUser()->login($model)) {
                    return $this->redirect('/profile/index');
                }
            } else {
                if ($model->sendActivationEmail($model)) {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'success',
                            'icon' => 'glyphicon glyphicon-envelope',
                            'message' => \Yii::t('app', 'Письмо с активацией отправленно на <strong> {email} </strong> (проверьте папку спам).', ['email' => $model->email]),
                        ]
                    );
                    return $this->redirect(Url::to(['/site/index']));
                } else {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'icon' => 'glyphicon glyphicon-envelope',
                            'message' => \Yii::t('app', 'Ошибка. Письмо не отправлено.'),
                        ]
                    );
                    \Yii::error(\Yii::t('app', 'Error. The letter was not sent.'));
                }
                return $this->refresh();
            }
            return $this->redirect('/profile/index');
        }
        return $this->render('office-signup', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', ['model' => $model]);
    }

    public function actionActivateAccount($key)
    {
        /* @var $modelUser User */

        /*if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }*/

        try {
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e) {
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'danger',
                    'icon'      => 'glyphicon glyphicon-envelope',
                    'message'   => \Yii::t('app', 'Неправильный ключ. Повторите регистрацию.'),
                ]
            );
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user = $user->activateAccount()) {
            /* @var $user User */
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'success',
                    'icon'      => 'glyphicon glyphicon-envelope',
                    'message'   => \Yii::t('app', 'Активация прошла успешно.'),
                ]
            );
            \Yii::$app->getUser()->login($user);
            return $this->redirect(['/profile/index']);
        } else {
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'danger',
                    'icon'      => 'glyphicon glyphicon-envelope',
                    'message'   => \Yii::t('app', 'Ошибка активации.'),
                ]
            );
        }

        return $this->redirect(Url::to(['/site/index']));
    }

    public function actionSocialauth()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;
                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);
                    // special redirect with closing popup window
                    $eauth->redirect("/site/continuesignup");
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

    public function actionContinuesignup()
    {
        if (!Yii::$app->user->isGuest)
            return $this->redirect('/profile/index');

        $model = new SignupForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $user = User::findOne($model->id);
            $user->attributes = $model->attributes;
            $user->type = 0;

            if ($user->validate() && !$model->hasErrors()) {
                $user->save();
                Yii::$app->user->login($user);
                return $this->redirect('/profile/index');
            } else {
                $model->addErrors($user->getErrors());
            }
        }


        $user = null;
        if (isset($_SESSION['eauth-state-vkontakte'])) {
            $social_id = $_SESSION['current_social_id'];
            $user = User::find()->where("vkontakte_id = :vid", [':vid' => $social_id])->one();
            if (!$user OR ($user AND $user->email == "")) {
                if (!$user)
                    $user = new User();
                $user->vkontakte_id = $social_id;
                $names = explode(" ", $_SESSION['user-' . $_SESSION['__id']]['profile']['name']);
                $user->first_name = isset($names[1]) ? $names[1] : "";
                $user->last_name = isset($names[0]) ? $names[0] : "";
                $user->count_friends_vk = $_SESSION['user-' . $_SESSION['__id']]['profile']['count_friends'];
                $user->save(false);
            } else if ($user) {
                session_destroy();
                $this->redirect('/site/signup?status=0');
            }

        } elseif (isset($_SESSION['eauth-state-facebook'])) {
            $social_id = $_SESSION['current_social_id'];
            $user = User::find()->where("facebook_id = :vid", [':vid' => $social_id])->one();
            if (!$user OR ($user AND $user->email == "")) {
                if (!$user)
                    $user = new User();
                $user->facebook_id = $social_id;
                $names = explode(" ", $_SESSION['user-' . $_SESSION['__id']]['profile']['name']);
                $user->first_name = isset($names[1]) ? $names[1] : "";
                $user->last_name = isset($names[0]) ? $names[0] : "";
                $user->count_friends_fb = $_SESSION['user-' . $_SESSION['__id']]['profile']['count_friends'];
                $user->save(false);
            } else if ($user){
                session_destroy();
                $this->redirect('/site/signup?status=0');
            }
        }
        $user->password = "";
        $model->attributes = $user->attributes;
        $model->id = $user->id;
        return $this->render("/site/continue_register", ['model' => $model]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSociallogin()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;
                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);
                    // special redirect with closing popup window
                    $eauth->redirect("/site/continuelogin");
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
                // close popup window and redirect to cancelUrl
//				$eauth->cancel();

                print_r($e->getMessage()); die;
                $eauth->redirect($eauth->getCancelUrl());
            }
        } else
            die(1);
    }

    public function actionAccept_social()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;
                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);
                    // special redirect with closing popup window
                    $eauth->redirect("/site/accept");
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
                // close popup window and redirect to cancelUrl
//				$eauth->cancel();

                print_r($e->getMessage()); die;
                $eauth->redirect($eauth->getCancelUrl());
            }
        } else
            die(1);
    }

    public function actionAccept()
    {
        $status = 1;
        $social_id = $_SESSION['current_social_id'];

        if (isset($_SESSION['eauth-state-facebook'])) {
            if (User::find()->where('facebook_id = :sid', [':sid' => $social_id])->one())
                $status = 0;
            else {
                $user = User::findOne(Yii::$app->user->getId());
                if ($user) {
                    $user->facebook_id = $social_id;
                    $user->count_friends_fb = $_SESSION['user-facebook-' . $social_id]['profile']['count_friends'];
                    $user->save(false);
                }
            }
        } elseif (isset($_SESSION['eauth-state-vkontakte'])) {
            if ( $user = User::find()->where('vkontakte_id = :sid', [':sid' => $social_id])->one())
                $status = 0;
            else {
                $user = User::findOne(Yii::$app->user->getId());
                if ($user) {
                    $user->vkontakte_id = $social_id;
                    $user->count_friends_vk = $_SESSION['user-vkontakte-' . $social_id]['profile']['count_friends'];
                    $user->save(false);
                }
            }
        }
        session_destroy();
        $this->redirect('/profile/index?status='.$status);


    }

    public function actionContinuelogin()
    {
        if (isset($_SESSION['eauth-state-vkontakte'])) {
            $social_id = $_SESSION['user-' . $_SESSION['__id']]['profile']['id'];
            $user = User::find()->where("vkontakte_id = :vid AND email <>''", [':vid' => $social_id])->one();
            if ($user) {
                Yii::$app->user->login($user, 3600 * 24 * 30);
                $this->redirect('/event/search');
            } else
                $this->redirect("/site/signup");
        }

        if (isset($_SESSION['eauth-state-facebook'])) {
            $social_id = $_SESSION['user-' . $_SESSION['__id']]['profile']['id'];
            $user = User::find()->where("facebook_id = :vid AND email <>''", [':vid' => $social_id])->one();
            if ($user) {
                Yii::$app->user->login($user, 3600*24*30);
                $this->redirect('/event/search');
            } else
                $this->redirect("/site/signup");
        }
    }

    public function actionTest()
    {
        User::getCountFriendsById();
    }

    public function actionAjaxacceptemail()
    {
        $user = User::findOne(Yii::$app->user->getId());
        if ($user) {
            $urlAccept = Url::to(['/site/acceptemail?hash='.md5($user->id . $user->email)], true);

            Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->params['siteName']])
                ->setTo($user->email)
                ->setSubject(Yii::t('app', 'accept_email'))
                ->setHtmlBody(Yii::t('app', 'confirm_email_go') .' <a href="'.$urlAccept.'">'. Yii::t('app', 'linke') .'</a>')
                ->send();

            $user->email_status = 1;
            $user->save(false);
        }
    }

    public function actionAcceptemail()
    {
        $user = User::findOne(Yii::$app->user->getId());
        if ($user AND $_GET['hash'] == md5($user->id . $user->email)) {
            $user->email_status = 2;
            $user->status = 1;
            $user->save(false);
            $this->redirect('/profile/index?status=1');
        }
    }
}




