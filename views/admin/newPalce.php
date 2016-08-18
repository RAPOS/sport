<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var $modelPlace \app\models\Place */
/** @var $countries \app\models\NetCountry */

?>

<div class="row" style=" margin-bottom: 32px;">
    <div class="col-lg-6">
        <?php if(isset($isEdit)): ?>
            <h1>Редактирование места #<?=$modelPlace->id?></h1>
        <?php else: ?>
            <h1>Новое место</h1>
        <?php endif; ?>
    </div>
</div>

<?php
    $form = ActiveForm::begin([
        'id' => 'new-place-form-admin',
        'enableAjaxValidation' => false,
        'options' => ['enctype'=>'multipart/form-data']
    ]);
?>
    <?= $form->field($modelPlace, 'name')->textInput(['placeholder' => Yii::t('app', 'placeholder_new_place_name')]) ?>

    <?= $form->field($modelPlace, 'country_id')->widget(Select2::classname(), [
            'data' => $countries,
            'options' => ['placeholder' => Yii::t('app', 'choice_country')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>
    <div id="place_for_city">
        <?=
            $form->field($modelPlace, 'city_id')->widget(Select2::classname(), [
                'data' => $cities,
                'options' => ['placeholder' => Yii::t('app', 'choice_country')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        ?>
    </div>

    <?= $form->field($modelPlace, 'adress')->textInput(['placeholder' => Yii::t('app', 'placeholder_new_place_address')]) ?>

    <?php
        echo $form->field($modelPlace, 'images[]')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*','multiple' => true],
            'pluginOptions' => [
                'uploadUrl' => Url::to(['/admin/upload']),
                'uploadExtraData' => [
                    'album_id' => 20,
                    'cat_id' => 'Nature'
                ],
                'initialPreview'=>$galary,
                'overwriteInitial'=>false,
                'showRemove' => true,
                'language' => 'ru',
                'showUpload' => false,
                'maxFileCount' => 3
            ]
        ]);
    ?>

    <div class="row">
        <div class="col-lg-12">
            <?= Html::submitButton("Cохранить", ['class' => 'btn btn-primary pull-left', 'name' => 'btn-pl-admin']) ?>
        </div>
    </div>

<?php
    ActiveForm::end();
?>

<style>.kv-file-upload{display: none;}</style>
