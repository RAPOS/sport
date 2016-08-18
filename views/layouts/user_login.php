<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\WLang;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <script type="text/javascript">

        var map = null;
        var geocoder = null;

        function initialize() {
            if (GBrowserIsCompatible()) {
                map = new GMap2(document.getElementById("map_canvas"));
                map.setCenter(new GLatLng(37.4419, -122.1419), 1);
                map.setUIToDefault();
                geocoder = new GClientGeocoder();
            }
        }

        function showAddress(address) {
            if (geocoder) {
                geocoder.getLatLng(
                    address,
                    function(point) {
                        if (!point) {
                            return false; ///Місце не знайдено на карті..
                        } else {
                            map.setCenter(point, 15);
                            var marker = new GMarker(point,{labelContent:111});

                            map.addOverlay(marker);

                            GEvent.addListener(marker, "click", function() {
                                marker.openInfoWindowHtml(address);
                            });
                            GEvent.trigger(marker, "click");
                        }
                    }
                );
            }
        }


    </script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body onload="initialize()" onunload="GUnload()">
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fa fa-futbol-o"></i> BLA SPORT',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            [
                'label' => "Вход",
                'url' => Url::toRoute(['/site/login']),
                'visible' => (Yii::$app->user->isGuest)
            ],
            [
                'label' => Yii::t('app', 'registration'),
                'url' => Url::toRoute(['/site/login']),
                'visible' => (Yii::$app->user->isGuest)
            ],
            [
                'label' => 'Блог',
                'url' => Url::toRoute(['/blog'])
            ],
            [
                'label' => Yii::t('app', 'event_search'),
                'url' => Url::toRoute(['/event/search']),
                'visible' => (!Yii::$app->user->isGuest)
            ],
            [
                'label' => "Панель адміністратора",
                'url' => Url::toRoute(['/admin/index']),
                 'visible' => (!Yii::$app->user->isGuest AND Yii::$app->user->identity->role == 10)
            ],
            [
                'label' => '<span class="glyphicon glyphicon-envelope"></span><span class="nav-notification badge">'.(\app\models\User::countNotReadMessage()).'</span>',
                'url' => Url::toRoute(['/notification/index']),
                'visible' => (!Yii::$app->user->isGuest)
            ],
            [
                'label' => '<span class="glyphicon glyphicon-bell"></span><span class="nav-notification badge">'.(\app\models\User::countNotReadNotification()).'</span>',
                'url' => Url::toRoute(['/notification/list']),
                'visible' => (!Yii::$app->user->isGuest)
            ],
            [
                'label' => '<span class="glyphicon glyphicon-user"></span> '.(!Yii::$app->user->isGuest?Yii::$app->user->identity->first_name." ".Yii::$app->user->identity->last_name:""),
                'items' => [
                    ['label' => 'Панель управления', 'url' => Url::toRoute(['profile/statistic'])],
                    ['label' => 'Создать событие', 'url' => Url::toRoute(['/event/add'])],
                    ['label' => Yii::t('app', 'my_events'), 'url' => Url::toRoute(['/event/myevents'])],
                    ['label' => Yii::t('app', 'profile'), 'url' => Url::toRoute(['/profile/index'])],
                    ['label' => Yii::t('app', 'reviews'), 'url' => Url::toRoute(['/profile/reviews'])],
                    ['label' => Yii::t('app', 'logout'), 'url' => Url::toRoute(['/site/logout']), 'linkOptions' => ['data-method' => 'post']],
                ],
                'visible' => (!Yii::$app->user->isGuest)
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?php if(!Yii::$app->user->isGuest): ?>
            <ul class="nav nav-pills nav-justified nav-user-menu">
                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/profile/statistic"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/profile/statistic'])?>">Панель управления</a>
                </li>
                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/event/myevents"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/event/myevents'])?>"><?=Yii::t('app','my_events')?></a>
                </li>
                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/notification/index"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/notification/index'])?>">
                        <?=Yii::t('app','messages')?>

                    </a>

                </li>
                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/profile/index"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/profile/index'])?>"><?=Yii::t('app','profile')?></a>
                </li>

                <?php if(Yii::$app->user->identity->type == 0): ?>
                    <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/event/myplacis"))?"active":""?>">
                        <a href="<?=Url::toRoute(['/event/myplacis'])?>">Мои места</a>
                    </li>
                <?php else: ?>
                    <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "/event/listgym"))?"active":""?>">
                        <a href="<?=Url::toRoute(['/event/listgym'])?>">Мои залы</a>
                    </li>
                <?php endif; ?>

                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "profile/reviews"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/profile/reviews'])?>"><?=Yii::t('app','reviews')?></a>
                </li>
                <li role="presentation" class="<?=(strstr($_SERVER['REQUEST_URI'], "notification/list"))?"active":""?>">
                    <a href="<?=Url::toRoute(['/notification/list'])?>">
                        Уведомления
                        <?php if ($count = \app\models\User::countNotReadMessage() > 0): ?>
                            <span class="nav-notification badge"><?=$count?></span>
                        <?php endif; ?>
                    </a>

                </li>
            </ul>
        <?php endif; ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <p class="pull-left">&copy; BlaSport <?= date('Y') ?></p>
            </div>
            <div class="col-lg-4">
                <?= WLang::widget();?>
            </div>
            <div class="col-lg-4">
                <p class="pull-right">Development  by <a rel="external" href="http://make-code.ru">make-code.ru</a></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script>
    function editAction(address){
        $("#place_for_palces select").html($("#PlaceForEddit").html())
        $("#map_canvas").show();
        showAddress(address);
    }

    $(document).ready(function(){
        $(".removePlaceGaalery").click(function(){
            var id = $(this).data("id");
            var curent = $(this);
            $.ajax({
                url : "/ajax/removeplace",
                method : "POST",
                data : {id : id},
                success: function(){
                    $(curent).closest('.file-preview-frame').hide();
                }
            });
        })
    })
</script>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw" type="text/javascript"></script>