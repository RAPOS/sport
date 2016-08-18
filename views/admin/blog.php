<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Блог';
?>
<div class="blog-index">
    <div class="row">
        <div class="col-lg-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-lg-6" style="margin-top: 20px;">
            <?= Html::a('Добавить', ['blog-add'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'date' => [
                'label' => 'Дата',
                'attribute' => 'date',
                'value' => function($model) {
                    return date('d.m.Y', $model->date);
                }
            ],
            'title',
            'text' => [
                'label' => 'Текст',
                'format' => 'raw',
                'value' => function($model) {
                    return HtmlPurifier::process($model->text);
                }
            ],

            [
                'class' => \yii\grid\ActionColumn::className(),
                'template' => '{update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return '<a data-confirm="Вы уверены, что хотите удалить этот элемент?" aria-label="Удалить" title="Удалить" href='. Url::toRoute(['blog-delete', 'id'=>$model->id]) .'><span class="glyphicon glyphicon-trash"></span></a>';
                    },
                    'update' => function ($url, $model) {
                        return '<a aria-label="Редактировать" title="Редактировать" href='. Url::toRoute(['blog-add', 'id'=> $model->id]) .'><span class="glyphicon glyphicon-pencil"></span></a>';
                    },
                ],
            ],
        ],
        'emptyText' => 'Еще нет новостей'
    ]); ?>
</div>