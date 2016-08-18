<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

/** @var $countries \app\models\NetCountry */
/** @var $listCities \app\models\NetCity */
/** @var $filterPlace \app\models\AdminPlaceFilterForm */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Управление местами';
?>

<div class="row">
    <div class="col-lg-6">
        <h1>База мест</h1>
    </div>
    <div class="col-lg-6" style="margin-top: 20px;">
        <a class=pull-right href="<?=Url::toRoute(['/admin/newplace'])?>">
            <button class="btn btn-primary">Создать новое</button>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="block">
            <div class="block-title"><?=Yii::t('app','filter_search')?></div>
            <div class="block-menu">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'signup-form',
                    'enableAjaxValidation' => false,
                ]); ?>

                <?= $form->field($filterPlace, 'country')->widget(Select2::classname(), [
                    'data' => $countries,
                    'options' => ['placeholder' => "Выберете страну"],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>

                <div id="place_for_city">
                    <?= $form->field($filterPlace, 'city')->widget(Select2::classname(), [
                        'data' => $listCities,
                        'options' => ['placeholder' => Yii::t('app', 'choice_town')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>

                <?= $form->field($filterPlace, 'status')->dropDownList([
                    '9' => "Все",
                    '0' => "Не подтверждено",
                    '1' => "Подтверждено"
                ]) ?>

                <div class="form-group">
                    <div class="text-center">
                        <?= Html::submitButton("Найти", ['class' => 'btn btn-block btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php  \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'name' => [
                    'label' => 'Название',
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->name, Url::toRoute(['event/place', 'id' => $model->id]));
                    }
                ],
                'country' => [
                    'attribute' => 'country',
                    'label' => 'Страна',
                    'value' => function($model) {
                        return $model->netCity->netCountry->name_ru;
                    }
                ],
                'city_id' => [
                    'attribute' => 'city_id',
                    'value' => function($model) {
                        return $model->netCity->name_ru;
                    }
                ],
                'adress',
                'status' => [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->getStatus(true);
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{update} {delete} {view}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return '<a data-pjax="0" data-method="post" data-confirm="Вы уверены, что хотите удалить этот элемент?" aria-label="Удалить" title="Удалить" href="'.Url::toRoute(['/admin/placedelete?id='.$model->id]).'"><span class="glyphicon glyphicon-trash"></span></a>';
                        },
                        'update' => function ($url, $model) {
                            return '<a data-pjax="0" aria-label="Редактировать" title="Редактировать" href="'.Url::toRoute(['/admin/placeedit?id='.$model->id]).'"><span class="glyphicon glyphicon-pencil"></span></a>';
                        },
                        'view' => function ($url, $model) {
                            return '<a data-pjax="0" aria-label="Просмотр" title="Просмотр" target="_blank" href="/event/place?id='.$model->id.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
                        },
                    ],
                ],
            ],
            'summaryOptions' => ['class' => 'text-right'],
            'emptyText' => 'Результатов не найдено',
        ]); ?>
    </div>
</div>