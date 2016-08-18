<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 07.08.2016
 * Time: 15:13
 */
/* @var $this yii\web\View */
/* @var $user \app\models\User */
/* @var $model \app\models\MailingForm */

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use phpnt\bootstrapNotify\BootstrapNotify;

$user = Yii::$app->user->identity;
?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= Yii::t('app', 'Уведомления на электронную почту: <strong>{email}</strong>', ['email' => $user->email]) ?>
        </h3>
    </div>
    <?php Pjax::begin(['enablePushState' => false]); ?>
    <?= BootstrapNotify::widget(); ?>
    <?php
    AwesomeBootstrapCheckboxAsset::register($this);
    ?>
    <?php $form = ActiveForm::begin([
        'id' => 'mailingForm',
        'action' => Url::to(['/profile/mailing-update']),
        'options' => [
            'data-pjax' => true
        ],
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
        <?= $form->field($model, 'private')
            ->checkbox([
                'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
            ]) ?>
        <?= $form->field($model, 'accept_declate_event')
            ->checkbox([
                'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
            ]) ?>
        <?= $form->field($model, 'new_bid_my_event')
            ->checkbox([
                'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
            ]) ?>
        <?= $form->field($model, 'event_soon')
            ->checkbox([
                'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
            ]) ?>
        <div class="col-md-12">
            <h5 style="padding-top: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;"><?= Yii::t('app', 'Получать емайл сообщения когда:') ?></h5>
        </div>
        <?= $form->field($model, 'event_for_me')
            ->checkbox([
                'template' => '<div class="col-md-12"><div class="checkbox checkbox-warning">{input}{beginLabel}{labelTitle}{endLabel}</div></div>',
            ]) ?>
        <div class="col-md-12" style="display: block; padding-top: 15px;"></div>
        <div class="form-group">
            <div class="col-lg-12 text-right">
                <?= Html::submitButton(Yii::t('app', 'Сохранить изменения'), ['class' => 'btn btn-warning', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
    <div class="clearfix"></div>
</div>

