<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use phpnt\cropper\ImageLoadWidget;

/* @var $this yii\web\View */
/* @var $model app\models\PlaceForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $user \app\models\User */

$user = Yii::$app->user->identity;
?>
<div class="box box-success" style="margin-bottom: 50px;">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= $model->isNewRecord ? Yii::t('app', 'Добавить место') : Yii::t('app', 'Изменить место') ?>
        </h3>
    </div>
    <?php
    Pjax::begin();
    ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $model->isNewRecord ? Url::to(['/place/create']) : Url::to(['/place/update', 'id' => $model->id]),
        'fieldConfig' => [
            'template' => '<div class="col-md-4">{label}</div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        {input}
                                        <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                                     </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom: 0;" class="col-md-8 col-md-offset-4"><i>{hint}</i></div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'
        ]
    ]); ?>
    <div class="box-body">
        <?= $form->field($model, 'name', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
            ->textInput([
                'maxlength' => true,
                'placeholder' => 'Например: Стадион "Юность"'
            ]) ?>
        <?php
        $model->country_id = $model->isNewRecord ? $user->country->name_ru : $model->city->region->countryFk->name_ru;
        ?>
        <?= $form->field($model, 'country_id', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
            ->textInput(['disabled' => true]);
        ?>
        <?php
        if ($model->city_name == null) {
            $model->city_name = $model->isNewRecord ? $user->getCityName() : $model->city->name_ru;
        }
        ?>
        <?= $form->field($model, 'city_name', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                            <div class="col-md-8 col-md-offset-4"><i>{hint}</i></div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
            ->textInput(['disabled' => true])->hint('Чтобы выбрать другой город, Вам нужно изменить текущее местоположение в вашем профиле.'); ?>

        <?php
        $model->city_id = $user->city_id;
        ?>
        <?= $form->field($model, 'city_id', [
            'template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false);?>

        <?= $form->field($model, 'address', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
            ->textInput(['maxlength' => true])
            ->error(['encode' => false]) ?>
        <?php ActiveForm::end(); ?>
        <?php
        Pjax::end();
        ?>
        <div class="col-md-12">
            <h4 style="padding-top: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;"><?= Yii::t('app', 'Загрузить фото места') ?></h4>
        </div>
        <div class="col-md-12">
            <?= ImageLoadWidget::widget([
                'id' => 'load-user-photos',                                     // суффикс ID
                'object_id' => $model->isNewRecord ? '0' : $model->id,            // ID объекта
                'imagesObject' => $model->isNewRecord ? $model->getTempPhotos() : $model->getPhotos(),                                // уже загруженные изображения
                'images_num' => 4,                                              // максимальное количество изображений
                'images_label' => 'place',                                      // метка для изображения
                'imageSmallWidth' => 750,                                       // ширина миниатюры
                'imageSmallHeight' => 750,                                      // высота миниатюры
                'imagePath' => '/uploads/avatars/',                             // путь, куда будут записыватся изображения относительно алиаса
                'noImage' => 3,                                                 // 1 - no-logo, 2 - no-avatar или путь к другой картинке
                'pluginOptions' => [                                            // настройки плагина
                    'aspectRatio' => 16/9,                                      // установите соотношение сторон рамки обрезки. По умолчанию свободное отношение.
                    'strict' => false,                                          // true - рамка не может вызодить за холст, false - может
                    'guides' => true,                                           // показывать пунктирные линии в рамке
                    'center' => true,                                           // показывать центр в рамке изображения изображения
                    'autoCrop' => true,                                         // показывать рамку обрезки при загрузке
                    'autoCropArea' => 0.5,                                      // площидь рамки на холсте изображения при autoCrop (1 = 100% - 0 - 0%)
                    'dragCrop' => true,                                         // создание новой рамки при клики в свободное место хоста (false - нельзя)
                    'movable' => true,                                          // перемещать изображение холста (false - нельзя)
                    'rotatable' => true,                                        // позволяет вращать изображение
                    'scalable' => true,                                         // мастабирование изображения
                    'zoomable' => false,
                ]]);
            ?>
        </div>
        <div class="col-md-12 text-right">
            <?= Html::button(Yii::t('app', 'Сохранить изменения'),
                [
                    'class' => 'btn btn-success',
                    'style' => 'margin-right: 15px; margin-bottom: 10px;',
                    'onclick' => '$("#form").submit();'
                ]
            ) ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
