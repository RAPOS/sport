<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use nex\datepicker\DatePicker;
use nex\datepicker\DatePickerAsset;
use app\assets\SliderAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EventForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $user \app\models\User */
?>
<?php
Pjax::begin([
    'id' => 'pjaxBlock',
    'enablePushState' => false
]);
?>
<div class="box box-success" style="margin-bottom: 50px;">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= $model->isNewRecord ? Yii::t('app', 'Создать событие') : Yii::t('app', 'Изменить событие') ?>
        </h3>
    </div>
    <?php
    BootstrapSelectAsset::register($this);
    AwesomeBootstrapCheckboxAsset::register($this);
    DatePickerAsset::register($this);
    SliderAsset::register($this);
    \app\assets\AppAsset::register($this);
    ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $model->isNewRecord ? Url::to(['/event/create']) : Url::to(['/event/update', 'id' => $model->id]),
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
        <div class="col-md-12">
            <h4 style="padding-top: 15px; padding-bottom: 30px; border-bottom: 1px solid #ccc;"><?= Yii::t('app', 'Тип события') ?></h4>
        </div>
        <?= $form->field($model, 'event_type', ['template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])->dropDownList($model->getEventTypeList(), [
            'class'     => 'form-control selectpicker',
            'data' => [
                'style' => 'btn-primary',
                'live-search' => false,
                'size' => 10,
                'title' => '---'
            ],
        ])->error(false);?>
        <div class="col-md-12" style="margin-bottom: 10px;"></div>
        <?= $form->field($model, 'type', ['template' => '<div class="col-md-12">{label}</div>{input}<div class="col-md-12">{error}</div>'])
            ->radioList(
                $model->getTypeList(),
                [
                    'class' => 'radio radio-primary',
                    'onchange' => '
                    $.pjax({
                        type: "POST",
                        url: "'.Url::to(['/event/set-schedule']).'",
                        data: jQuery("#form").serialize(),
                        container: "#pjaxBlock",
                        push: false,
                        scrollTo: false
                    })',
                    'item' => function ($index, $label, $name, $checked, $value){
                        return '<div class="col-xs-6"><input type="radio" id="check-h-'.$index.'" name="EventForm[type]" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            <label for="check-h-'.$index.'">'.$label.'</label></div>';
                    }
                ])->hint(false)->error(false); ?>
        <?php
        if ($model->type != null):
            ?>
            <div class="col-md-12" style="margin-bottom: 20px;">
                <h4 style="padding-top: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;"><?= Yii::t('app', 'Дата и время') ?></h4>
            </div>
            <?php
            if ($model->type == 1):
                ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'start_date', ['template' => '<div class="col-md-12">{label}</div>
                        <div class="col-md-12">
                                    <div class="input-group">
                                        {input}
                            </div>
                        </div>
                        <div style="margin-bottom: 0;" class="col-md-12"><i>{hint}</i></div>
                        <div class="col-md-12">{error}</div>'])->widget(
                        DatePicker::className(), [
                        'language' => 'ru',
                        'size' => 'sm',
                        //'value' => Yii::$app->formatter->asDatetime(time()),
                        'template' => '{input}{addon}{dropdown}',
                        'addon' => '<span class="input-group-addon" style="border-left: none;"><i class="glyphicon glyphicon-calendar"></i></span>',
                        'clientOptions' => [
                            'defaultDate'       => Yii::$app->formatter->asDate(strtotime('+1 hour', time()), "php:Y-m-d H:m"),
                            'format'            => 'L H:mm',
                            'stepping'          => 30,
                            'minDate'           => Yii::$app->formatter->asDate(time(), "php:d/m/Y H:m"),
                            'maxDate'           => Yii::$app->formatter->asDate(strtotime('+1 month', time()), "php:Y-m-d"),
                            'allowInputToggle'  => true,
                            'showClose'         => true
                        ],
                    ])->hint('Укажите начало события.');?>
                </div>
                <div class="col-sm-8">
                    <?= $form->field($model, 'duration', ['template' => '<div class="col-md-12">{label}</div> 
                        <div class="col-md-12" style="padding-left: 30px !important; padding-right: 30px !important;">{input}</div>
                        <div class="col-md-12"><i>{hint}</i></div>'])->textInput([
                        'class' => 'slider form-control',
                        'data' => [
                            'provide'   => 'slider',
                            'slider-ticks' => "[30, 60, 90, 120, 150, 180]",
                            'slider-ticks-labels' => '["30 мин", "1 час", "1 час 30 мин", "2 часа", "2 час 30 мин", "3 часа"]',
                            'slider-min' => '30',
                            'slider-max' => '180',
                            'slider-step' => '30',
                            'slider-value' => '90',
                            'slider-tooltip' => 'hide',
                        ]
                    ])->hint('Укажите длительность события (от 30 минут до 3 часов.)') ?>
                </div>
                <?php
            elseif ($model->type == 0):
                ?>

                <?php
            endif;
            ?>
            <?php
        endif;
        ?>


            <?/*= $form->field($model, 'day_1')
                ->checkbox([
                    'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
                ]) */?>


        <?/*= $form->field($model, 'price')->textInput(['maxlength' => true]) */?><!--

    <?/*= $form->field($model, 'date_start')->textInput() */?>

    <?/*= $form->field($model, 'date_end')->textInput() */?>

    <?/*= $form->field($model, 'count_place')->textInput() */?>

    <?/*= $form->field($model, 'free_count_place')->textInput() */?>

    <?/*= $form->field($model, 'description')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'min_count_place')->textInput() */?>

    <?/*= $form->field($model, 'max_count_place')->textInput() */?>

    <?/*= $form->field($model, 'coach')->textInput() */?>

    <?/*= $form->field($model, 'duration')->textInput() */?>

    <?/*= $form->field($model, 'constantly_day')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'constantly_time')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'count_views')->textInput() */?>

    <?/*= $form->field($model, 'recalculate_price')->textInput() */?>

    <?/*= $form->field($model, 'city_id')->textInput() */?>

    --><?/*= $form->field($model, 'place_id')->textInput() */?>
        <div class="col-md-12" style="display: block; padding-top: 30px;"></div>
        <div class="form-group">
            <div class="col-lg-12 text-right">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Создать событие') : Yii::t('app', 'Изменить событие'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
Pjax::end();
?>
