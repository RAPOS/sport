<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $user \app\models\User */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\LanguageSelect\LanguageSelect;
use yii\helpers\Url;
use phpnt\fontAwesome\FontAwesomeAsset;
use phpnt\adminLTE\AdminLteAsset;
use yii\bootstrap\Tabs;

AdminLteAsset::register($this);
FontAwesomeAsset::register($this);
AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $user = Yii::$app->user->identity;
    $name = ($user->company_name != null) ? $user->company_name : null;
    $name = ($name === null) ? $user->first_name.' '.$user->last_name : $name;
    $avatar = isset($user->profilePhoto->file_small) ? $user->profilePhoto->file_small : Yii::$app->urlManager->baseUrl.'/img/no-avatar.png';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="skin-blue sidebar-mini" style="background-color: #ecf0f5;">
<?php $this->beginBody() ?>

<div class="wrap <?= (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? 'head-index' : '' ?>" style="background-color: #ecf0f5;">
    <header class="main-header">
        <?php
        NavBar::begin([
            'brandLabel' => '<i class="fa fa-futbol-o"></i> '.Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'id' => 'main-menu',
                'class' => 'navbar-default navbar-fixed-top',
            ],
        ]);

        /*if (Yii::$app->user->can('moderator')) {
            $menuItems[] = ['label' => Yii::t('app', 'Панель управления'), 'url' => Url::to(['/site/admin'])];
        }*/

        $menuItems[] = ['label' => 'Блог', 'url' => Url::to(['/blog'])];
        $menuItems[] = ['label' => Yii::t('app', 'Поиск события'),'url' => Url::to(['/event/search'])];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Как это работает?', 'url' => Url::to(['/site/about'])];
            $menuItems[] = ['label' => Yii::t('app', 'Регистрация'), 'url' => ['/site/user-signup']];
            $menuItems[] = ['label' => Yii::t('app', 'Войти'), 'url' => ['/site/login']];
        } else {
            $menuItems[] = ['label' => Yii::t('app', 'Статистика'), 'url' => ['/profile/statistic']];
            $menuItems[] = [
                'linkOptions'   => ['class' => 'dropdown-toggle', 'aria-expanded' => "true"],
                'options'   => ['class' => 'dropdown messages-menu'],
                'label' => '<i class="fa fa-envelope-o"></i><span class="label label-success">1</span>',
                'items' => [
                    [
                        'label' => Yii::t('app', 'У Вас {countMessages} новых сообщений', ['countMessages' => 1]),
                        'options' => ['class' => 'header']
                    ],
                    [
                        'label' => '',
                        'linkOptions'   => ['style' => 'display: none;'],
                        'submenuOptions'   => [
                            'class' => 'menu',
                            'style' => 'overflow: hidden; width: 100%;',
                        ],
                        'items' => [
                            [
                                'label' => '<div class="pull-left">'.Html::img(Yii::$app->urlManager->baseUrl.'/img/admin.png', ['class' => 'img-circle']).'</div>
                                            <h4>Служба поддержки<small><i class="fa fa-clock-o"></i> 5 mins</small></h4>
                                            <p>Будут вопросы пишите.</p>',
                                'url' => '#'
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Посмотреть все сообщения'),
                        'url' => Url::to(['/notification/index']),
                        'options' => [
                            'class' => 'footer'
                        ]
                    ],
                ],
            ];
            $menuItems[] = [
                'linkOptions'   => ['class' => 'dropdown-toggle', 'aria-expanded' => "true"],
                'options'   => ['class' => 'dropdown notifications-menu'],
                'label' => '<i class="fa fa-bell-o"></i><span class="label label-warning">1</span>',
                'items' => [
                    [
                        'label' => Yii::t('app', 'У Вас {countMessages} новых уведомлений', ['countMessages' => 1]),
                        'options' => ['class' => 'header']
                    ],
                    [
                        'label' => '',
                        'linkOptions'   => ['style' => 'display: none;'],
                        'submenuOptions'   => [
                            'class' => 'menu',
                            'style' => 'overflow: hidden; width: 100%;',
                        ],
                        'items' => [
                            [
                                'label' => '<i class="fa fa-futbol-o "></i>Добро пожаловать на сайт!',
                                'url' => '#'
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Посмотреть все уведомления'),
                        'url' => Url::to(['/notification/list']),
                        'options' => [
                            'class' => 'footer'
                        ]
                    ],
                ],
            ];
            $menuItems[] = [
                'linkOptions' => ['class' => 'dropdown-toggle'],
                'options' => ['class' => 'dropdown user user-menu'],
                'itemsOptions'  => ['class' => 'user-body'],
                'label' => '<img src="'.$avatar.'" class="user-image" alt="User Image"><span class="hidden-xs">'.$name.'</span>',
                'items' => [
                    [
                        'label' => '<img src="'.$avatar.'" class="img-circle">
                            <p>'.$name.'<small>'.Yii::t('app', 'Зарегистрирован').' '.Yii::$app->formatter->asDate($user->created_at).'</small></p>',
                        'options'  => [
                            'class' => 'user-header'
                        ]
                    ],
                    ['label' => '<div class="pull-left">'.Html::a(Yii::t('app', 'Мой профиль'), Url::to(['/profile/index']), ['class' => 'btn btn-info btn-flat']).'</div>
                                    <div class="pull-right">
                                      '. Html::beginForm(['/site/logout'], 'post')
                                                . Html::submitButton(
                                                    Yii::t('app', 'Выйти'),
                                                    ['class' => 'btn btn-info btn-flat']
                                                )
                                                . Html::endForm()
                                                .'
                                    </div>',
                        'options' => [
                            'class' => 'user-footer'
                        ],
                    ],
                ],
            ];
        }
        //echo LanguageSelect::widget();
        ?>
        <div class="navbar-custom-menu">
            <?= Nav::widget([
                'options' => ['class' => 'nav navbar-nav'],
                'items' => $menuItems,
                'encodeLabels' => false
            ]);
            ?>
        </div>
        <?php
        NavBar::end();
        ?>
    </header>

    <?php
    if (!Yii::$app->user->isGuest):
    ?>
    <div class="container" style="margin-top: 40px; margin-bottom: 70px;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="nav-tabs-custom">
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Мои события'),
                    'url'       => Url::to(['/event/index']),
                    'active'    => Yii::$app->controller->id == 'event',
                ],
                [
                    'label'     => Yii::t('app', 'Мои места'),
                    'url'       => Url::to(['/place/index']),
                    'active'    => Yii::$app->controller->id == 'place',
                    'visible'   => Yii::$app->user->can('user')
                ],
                [
                    'label' => Yii::t('app', 'Сообщения'),
                    'url' => '#',
                ],
                [
                    'label' => Yii::t('app', 'Отзывы'),
                    'url' => '#',
                ],
                [
                    'label' => Yii::t('app', 'Надежность'),
                    'url' => '#',
                ],
                [
                    'label' => Yii::t('app', 'Мой профиль'),
                    //'content' => $content,
                    'url' => Yii::$app->user->can('office') ? Url::to(['/profile/office-profile']) : Url::to(['/profile/user-profile']),
                    'active' => (Yii::$app->controller->id == 'profile' && (Yii::$app->controller->action->id == 'user-profile' || Yii::$app->controller->action->id == 'office-profile'))
                ],
                [
                    'label' => Yii::t('app', 'Предпочтения'),
                    'url' => '/profile/setup',
                    'active' => (Yii::$app->controller->id == 'profile' && Yii::$app->controller->action->id == 'setup'),
                    'visible'   => Yii::$app->user->can('user')
                ],
            ],
            'itemOptions' => [
                'class' => 'row',
                'style' => 'padding: 10px;'
            ],
            'options' => [
                'class' => 'nav nav-tabs'
            ]
        ]);
        ?>
        <?= $content ?>
        </div>
    </div>
    <?php
    else:
    ?>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    <?php
    endif;
    ?>
</div>
<footer style="border-top: 1px solid #d2d6de; color: #ffffff; padding: 15px; background-color: #3c8dbc;">
    <div class="container">
        <div class="col-md-4">
            <p class="pull-left">&copy; <?= Yii::$app->name.' '.date('Y') ?></p>
        </div>
        <!--<div class="col-lg-4">

        </div>
        <div class="col-lg-4">
            <p class="pull-right">Development  by <a rel="external" href="http://make-code.ru">make-code.ru</a></p>
        </div>-->
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script>
    /*function editAction(address){
     $("#place_for_palces select").html($("#PlaceForEddit").html())
     $("#map_canvas").show();
     showAddress(address);
     }*/
</script>