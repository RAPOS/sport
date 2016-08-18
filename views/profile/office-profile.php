<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 05.08.2016
 * Time: 17:17
 */
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
?>
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

        <div class="form-group">
            <div class="col-lg-12 text-center">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


