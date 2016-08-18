
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'registration');
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-offset-2 col-lg-8">
            <div class="block">
                <div class="block-title">Зевершение регистрации</div>
                <div class="block-menu">
                    <?php $form = ActiveForm::begin([
                        'id' => 'signup-form',
                        'enableAjaxValidation' => false,
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => '{label}<div class="col-lg-10">{input}</div><div class="col-lg-offset-2 col-lg-10">{error}</div>',
                            'labelOptions' => ['class' => 'col-lg-2'],
                        ],
                    ]); ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'last_name') ?>

                    <?= $form->field($model, 'first_name') ?>

                    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

                    <?= $form->field($model, 'sex')->dropDownList([
                        '1' => Yii::t('app', 'male'),
                        '2' => Yii::t('app', 'female'),
                    ]) ?>


                    <?= $form->field($model, 'b_date')->widget(DatePicker::className(), [
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]) ?>

                    <div class="form-group">
                        <div class="col-lg-12 text-center">
                            <?= Html::submitButton(Yii::t('app', 'sing_up'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-offset-2"></div>
    </div>
</div>

