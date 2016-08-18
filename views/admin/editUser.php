<?php

/** @var $modelUser \app\models\User */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

$this->title = 'Редактирование пользователя';
?>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-12">
        <h1>Редактирование пользователя #<?=$modelUser->id?></h1>
    </div>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'edit-user-form-admin',
    'enableAjaxValidation' => false,
    'options' => ['enctype'=>'multipart/form-data']
]); ?>

    <?= $form->field($modelUser, 'email')->textInput(['disabled' => true, 'readonly' => true]) ?>

    <?= $form->field($modelUser, 'last_name')->textInput() ?>

    <?= $form->field($modelUser, 'first_name')->textInput() ?>

    <?= $form->field($modelUser, 'sex')->dropDownList([
        '1' => Yii::t('app', 'male'),
        '2' => Yii::t('app', 'female'),
    ]) ?>

    <?= $form->field($modelUser, 'status')->dropDownList([
        '0' => "На модерации",
        '1' => "Активен",
        '2' => "Заблокирован"
    ]) ?>

    <?= $form->field($modelUser, 'type')->dropDownList([
        '0' => "Физичиское лицо",
        '1' => "Юридичиское лицо"
    ]) ?>

    <?= $form->field($modelUser, 'b_date')->widget(DatePicker::className(), [
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>

    <div class="row">
        <div class="col-lg-12">
            <?= Html::submitButton("Сохранить", ['class' => 'btn btn-primary pull-left', 'name' => 'btn-pl-admin']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
