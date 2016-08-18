<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use phpnt\oAuth\AuthChoice;
use phpnt\bootstrapNotify\BootstrapNotify;

$this->title = Yii::t('app', 'authorization');
?>
<?= BootstrapNotify::widget() ?>
<div class="site-login">
    <div class="row">
        <div class="col-md-6" style="margin-top: 30px;">
            <h1>Добро пожаловать на наш сайт!</h1>
            <h3>Авторизация через:</h3>
            <div style="text-align: center">
                <?= AuthChoice::widget([
                    'baseAuthUrl' => ['/auth/index'],
                ]) ?>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 40px; margin-bottom: 40px;">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <?php $form = ActiveForm::begin([
                    'id' => 'loginForm',
                    //'action' => $model->scenario == 'entity' ? Url::to(['/site/signup', 'entity' => 1]) : Url::to(['/site/signup']),
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
                    <?= $form->field($model, 'email', ['parts' => ['{font-awesome}' => 'envelope-o']])->textInput(['placeholder' => 'Email']) ?>

                    <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])->passwordInput(['placeholder' => 'Пароль']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="col-xs-12">{input} {label}</div>'
                    ]) ?>

                    <div class="form-group">
                        <div class="col-lg-12 text-center">
                            <?= Html::submitButton(Yii::t('app', 'login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
