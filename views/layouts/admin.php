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
                            var marker = new GMarker(point, {draggable: true});
                            map.addOverlay(marker);
                            GEvent.addListener(marker, "dragend", function() {
                                marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
                            });
                            GEvent.addListener(marker, "click", function() {
                                marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
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
        'brandLabel' => 'Админка',
        'brandUrl' => '/admin/index',
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            [
                'label' => "Пользователи",
                'url' => Url::toRoute(['/admin/users']),
                'items' => [
                    ['label' => "Cписок пользователей", 'url' => Url::toRoute(['/admin/users'])],
                    ['label' => "Модерация аватарок", 'url' => Url::toRoute(['/admin/avatarmoderation'])]
                ]

            ],
            [
                'label' => "Места",
                'url' => Url::toRoute(['/admin/place'])
            ],
            [
                'label' => "Блог",
                'url' => Url::toRoute(['/admin/blog']),
            ],
            [
                'label' => '<span class="glyphicon glyphicon-user"></span> '. Yii::$app->user->identity->first_name." ".Yii::$app->user->identity->last_name,
                'items' => [
                    ['label' => Yii::t('app', 'control_panel'), 'url' => Url::toRoute(['/profile/statistic'])],
                    ['label' => Yii::t('app', 'my_events'), 'url' => Url::toRoute(['/event/index'])],
                    ['label' => Yii::t('app', 'profile'), 'url' => Url::toRoute(['#'])],
                    ['label' => Yii::t('app', 'reviews'), 'url' => Url::toRoute(['#'])],
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
        <div class="row">
            <div class="col-lg-4">
                <p class="pull-left">&copy; Blacar <?= date('Y') ?></p>
            </div>
            <div class="col-lg-4">
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