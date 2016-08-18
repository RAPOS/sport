<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'date_start') ?>

    <?= $form->field($model, 'date_end') ?>

    <?php // echo $form->field($model, 'count_place') ?>

    <?php // echo $form->field($model, 'free_count_place') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'min_count_place') ?>

    <?php // echo $form->field($model, 'max_count_place') ?>

    <?php // echo $form->field($model, 'coach') ?>

    <?php // echo $form->field($model, 'duration') ?>

    <?php // echo $form->field($model, 'constantly_day') ?>

    <?php // echo $form->field($model, 'constantly_time') ?>

    <?php // echo $form->field($model, 'count_views') ?>

    <?php // echo $form->field($model, 'recalculate_price') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'timezone') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'event_type') ?>

    <?php // echo $form->field($model, 'place_id') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
