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
                'label' => "Панель адміністратора",
                'url' => Url::toRoute(['/admin/index']),
                'visible' => (Yii::$app->user->identity->role == 10)
            ],
            [
                'label' => '<span class="glyphicon glyphicon-envelope"></span><span class="nav-notification badge">'.(\app\models\User::countNotReadMessage()).'</span>',
                'url' => Url::toRoute(['/notification/index']),
                'visible' => (!Yii::$app->user->isGuest)
            ],
            [
                'label' => '<span class="glyphicon glyphicon-bell"></span><span class="nav-notification badge">'.(\app\models\User::countNotReadNotification()).'</span>',
                'url' => Url::toRoute(['/notification/list'])
            ],
            [
                'label' => '<span class="glyphicon glyphicon-user"></span> '.Yii::$app->user->identity->first_name." ".Yii::$app->user->identity->last_name,
                'items' => [
                    ['label' => 'Панель управления', 'url' => Url::toRoute(['profile/statistic'])],
                    ['label' => 'Создать событие', 'url' => Url::toRoute(['/event/add'])],
                    ['label' => Yii::t('app', 'my_events'), 'url' => Url::toRoute(['/event/myevents'])],
                    ['label' => Yii::t('app', 'profile'), 'url' => Url::toRoute(['/profile/index'])],
                    ['label' => Yii::t('app', 'reviews'), 'url' => Url::toRoute(['/profile/reviews'])],
                    ['label' => Yii::t('app', 'logout'), 'url' => Url::toRoute(['/site/logout']), 'linkOptions' => ['data-method' => 'post']],
                ],
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
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
</script>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw" type="text/javascript"></script>