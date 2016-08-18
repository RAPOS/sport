<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'price',
            'date_start',
            'date_end',
            'count_place',
            'free_count_place',
            'description',
            'min_count_place',
            'max_count_place',
            'coach',
            'duration',
            'constantly_day',
            'constantly_time',
            'count_views',
            'recalculate_price',
            'status',
            'timezone:datetime',
            'city_id',
            'event_type',
            'place_id',
            'user_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
