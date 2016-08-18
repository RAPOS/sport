<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 07.08.2016
 * Time: 21:16
 */
/* Вывод всех продуктов */
/*
 * Принимеет следующие свойства:
 *      $modelAdMain - объект элемента
 *      $key - id элемента
 *      $index - порядковый номер элемента от 0. На каждой странице считается снова
 *      $widget - объект виджета
*/

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\Carousel;

/* @var $this yii\web\View */
/* @var $model \app\models\Place */
?>
<div class="box <?= $model->getStatusClass() ?> box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $model->name ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <p><?= Yii::t('app', 'Статус').': <strong>'.$model->getStatusName().'</strong>' ?></p>
        <?= $model->getCarouselPhotos() ? Carousel::widget([
            'items' => $model->getCarouselPhotos(),
            'options' => [
                'data-interval' => 0,
                'class' => 'carousel slide'
            ],
            'controls' => ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],     // Стрелочки вперед - назад
            'showIndicators' => true,                   // отображать индикаторы (кругляшки)
        ]) : Html::img(Yii::$app->urlManager->baseUrl.'/img/no-image-box.png', ['style' => 'width: 100%;']); ?>
        <p style="margin-top: 15px;"><?= Yii::t('app', 'Адрес').': <strong>'.$model->getFullAddress().'</strong>' ?></p>
        <div class="pull-left"><?= Html::a(Yii::t('app', 'Изменить'), Url::to(['/place/update', 'id' => $model->id]), ['class' => 'btn btn-block btn-primary btn-xs']) ?></div>
        <div class="pull-right">
            <?= Html::beginForm([Url::to(['/place/delete', 'id' => $model->id])], 'post')
            . Html::submitButton(
            Yii::t('app', 'Удалить'),['class' => 'btn btn-block btn-danger btn-xs', 'onclick' => 'return confirm("Вы уверены, что хотите удалить это место?")',]
            )
            . Html::endForm() ?>
        </div>
    </div>
    <!-- /.box-body -->
</div>
