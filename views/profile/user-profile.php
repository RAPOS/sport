<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 05.08.2016
 * Time: 16:20
 */
/* @var $model \app\models\UserForm */
/* @var $modelPassword \app\models\ChangePassword */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use nex\datepicker\DatePicker;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\widgets\MaskedInput;
use yii\helpers\Url;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;
use yii\widgets\Pjax;
?>
<?php Pjax::begin([
    'id' => 'userPjaxBlock'
]) ?>
<?php
BootstrapSelectAsset::register($this);
AwesomeBootstrapCheckboxAsset::register($this);
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= Html::encode($this->title) ?>
        </h3>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'userForm',
        'action' => Url::to(['/profile/user-profile']),
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
        <div class="col-md-6">
            <?= $form->field($model, 'first_name', [
                'template' => '<div class="row"><div class="col-md-12">{label}</div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            {input}
                                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">{error}</div></div>',
                'parts' => ['{font-awesome}' => 'user'],
            ])->textInput(['placeholder' => 'Имя'])->error(false) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'last_name', [
                'template' => '<div class="row"><div class="col-md-12">{label}</div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            {input}
                                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">{error}</div></div>',
                'parts' => ['{font-awesome}' => 'user'],
            ])->textInput(['placeholder' => 'Фамилия'])->error(false) ?>
        </div>
        <div class="col-md-12">
        <?= $form->field($model, 'description', [
            'template' => '<div class="row">
                            <div class="col-md-12">{label}</div>
                            <div class="col-md-12">{input}</div>
                            <div class="col-md-12"><i>{hint}</i></div>
                            </div>'])->textarea(['rows' => 4, 'style' => 'text-align: justify; resize: vertical;'])
            ->hint('<p style="text-align: justify;">Каким видом спорта Вы увлекаетесь? Какое у Вас хобби или интересы?</p>'); ?>
        </div>

        <?= $form->field($model, 'sex', ['template' => '<div class="col-md-12">{label}</div>{input}<div class="col-md-12">{error}</div>'])
            ->radioList(
                $model->getSexArray(),
                [
                    'class' => 'radio radio-primary',
                    'item' => function ($index, $label, $name, $checked, $value){
                        return '<div class="col-xs-6"><input type="radio" id="check-h-'.$index.'" name="UserForm[sex]" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            <label for="check-h-'.$index.'">'.$label.'</label></div>';
                    }
                ])->hint(false)->error(false); ?>
        <div class="col-md-12">
            <h4 style="padding-top: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">Дата рождения <span style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Будет виден только Ваш возраст."><i class="fa fa-question-circle" aria-hidden="true"></i></span></h4>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'day', ['template' => '
                            <div class="row">
                                <div class="col-xs-12">{label}</div>
                                <div class="col-xs-12" style="float: left;">{input}<span aria-hidden="true" class="glyphicon glyphicon-{glyph} form-control-feedback"></span></div>
                                <div class="col-xs-12">{error}</div>
                            </div>'])->dropDownList($model->getDaysList(), [
                'class'     => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => false,
                    'size' => 10,
                    'title' => '---'
                ],
            ])->error(false);?>
        </div>
        <div id="monthInput" class="col-xs-4">
            <?= $form->field($model, 'month', ['template' => '
                            <div class="row">
                                <div class="col-xs-12">{label}</div>
                                <div class="col-xs-12" style="float: left;">{input}<span aria-hidden="true" class="glyphicon glyphicon-{glyph} form-control-feedback"></span></div>
                                <div class="col-xs-12">{error}</div>
                            </div>
'       ])->widget(
                DatePicker::className(), [
                'language' => 'ru',
                'template' => '{input}{addon}{dropdown}',
                'addon' => '<span class="input-group-addon" style="border-left: none;"><i class="glyphicon glyphicon-calendar"></i></span>',
                'clientOptions' => [
                    'dayViewHeaderFormat'   => 'MMMM',
                    'viewMode'          => 'months',
                    //'defaultDate'       => Yii::$app->formatter->asDate(strtotime(time('1970/1/1')), "php:Y-m-d"),
                    'format'            => 'MMMM',
                    'showTodayButton'   => false,
                    'showClear'         => false,
                    'allowInputToggle'  => true,
                    'showClose'         => true
                ],
            ])->error(false);?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'year', [
                'template' => '
                            <div class="row">
                                <div class="col-xs-12">{label}</div>
                                <div class="col-xs-12" style="float: left;">{input}<span aria-hidden="true" class="glyphicon glyphicon-{glyph} form-control-feedback"></span></div>
                                <div class="col-xs-12">{error}</div>
                            </div>
'       ])->widget(DatePicker::className(), [
                'language' => 'ru',
                'template' => '{input}{addon}{dropdown}',
                'addon' => '<span class="input-group-addon" style="border-left: none;"><i class="glyphicon glyphicon-calendar"></i></span>',
                'clientOptions' => [
                    'viewMode'          => 'years',
                    'defaultDate'       => Yii::$app->formatter->asDate(strtotime('-25 year', time()), "php:Y-m-d"),
                    'format'            => 'Y',
                    'minDate'           => Yii::$app->formatter->asDate(strtotime('-70 year', time()), "php:Y-m-d"),
                    'maxDate'           => Yii::$app->formatter->asDate(strtotime('-18 year', time()), "php:Y-m-d"),
                    'showTodayButton'   => false,
                    'showClear'         => false,
                    'allowInputToggle'  => true,
                    'showClose'         => true
                ],
            ])->error(false);?>
        </div>
        <div class="col-md-12"><i>Будет виден только Ваш возраст.</i></div>
        <div class="col-md-12" style="display: block; padding-top: 30px;"></div>
        <?php
        if ($model->country_id == null && Yii::$app->geoData->country) {
            $model->country_id = Yii::$app->geoData->country;
        }
        ?>
        <?= $form->field($model, 'country_id', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
            ->dropDownList($model->countriesList, [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-primary',
                    'live-search' => 'false',
                    'size' => 10,
                    //'title' => Yii::t('app', 'Выберите страну'),
                ],
                'onchange' => '
                    $.pjax({
                        type: "POST",
                        url: "'.Url::to(['/geo/set-country']).'",
                        data: jQuery("#userForm").serialize(),
                        container: "#userPjaxBlock",
                        push: false,
                        scrollTo: false
                    })']) ?>
        <?php
        $engine = new Bloodhound([
            'name' => 'countriesEngine',
            'clientOptions' => [
                'datumTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                'queryTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                'remote' => [
                    'url' => Url::to(['/ajax/search-city', 'id'=> $model->country_id, 'q'=>'QRY']),
                    'wildcard' => 'QRY'
                ]
            ]
        ]);
        ?>
        <?php
        if ($model->city == null) {
            $model->city = $model->getCityName();
        }
        ?>
        <?= $form->field($model, 'city', [
            'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])->widget(
            TypeAhead::className(),
            [
                'options' => ['class' => 'form-control'],
                'engines' => [ $engine ],
                'clientOptions' => [
                    'highlight' => true,
                    'minLength' => 2,
                ],
                'clientEvents' => [
                    'typeahead:selected' => new \yii\web\JsExpression(
                        'function(obj, datum, name) { 
                                        $("#city-id").val(datum.id);
                                    }'
                    ),
                ],
                'dataSets' => [
                    [
                        'name' => 'city',
                        'displayKey' => 'city',
                        'source' => $engine->getAdapterScript(),
                        'templates' => [
                            'suggestion' => new \yii\web\JsExpression("function(data){ return '<div class=\'col-xs-12 item\'><div style=\'margin: 0; padding: 0; float: none;\'>' + data.city + '</div><div style=\'font-size: 10px; margin: 0; padding: 0; float: none;\'>' + data.region + '</div></div>'; }"),
                        ],
                    ]
                ]
            ]
        );?>

        <?php
        if ($model->city == null && Yii::$app->geoData->city) {
            $model->city = Yii::$app->geoData->city;
        }
        ?>

        <?= $form->field($model, 'city_id', [
            'template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false);?>
        <?= $form->field($model, 'type', [
            'template' => '{input}'])
            ->hiddenInput(['value' => '1'])->label(false) ?>

        <?php
        if ($model->email != null && $model->email_status != null):
            ?>
            <?= $form->field($model, 'email', ['parts' => ['{font-awesome}' => 'envelope-o']])
            ->textInput(['disabled' => true]) ?>
            <?php
        else:
            ?>
            <?= $form->field($model, 'email', ['parts' => ['{font-awesome}' => 'envelope-o']])
            ->textInput(['placeholder' => 'Емайл'])
            ->label('Эл. почта (не подтверждена)')
            ->hint(Yii::t('app', '*После сохранения профиля, на указанную эл. почту будет выслано письмо, для ее подтверждения.')) ?>
            <?php
        endif;
        ?>

        <?= $form->field($model, 'phone', [
            'template' => '<div class="col-md-4">{label}</div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">+'.$model->getPhoneCode($model->country_id).'</span>
                                            {input}
                                        <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                                     </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom: 0;" class="col-md-8 col-md-offset-4"><i>{hint}</i></div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>',
            'parts' => ['{font-awesome}' => 'phone']])
            ->widget(MaskedInput::className(),[
                'name' => 'phone_num',
                'mask' => $model->getPhoneMask($model->country_id)
            ])
            ->hint('*Ваш номер телефона') ?>

        <?= Html::hiddenInput('model', 'app\models\UserForm') ?>
        <?= Html::hiddenInput('scenario', $model->scenario) ?>
        <?= Html::hiddenInput('form', '@app/views/profile/user-profile') ?>

        <div class="col-md-12" style="display: block; padding-top: 30px;"></div>
        <div class="form-group">
            <div class="col-lg-12 text-right">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="clearfix"></div>
</div>
<?php Pjax::end() ?>
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?= Yii::t('app', 'Изменение пароля') ?>
        </h3>
    </div>
    <?php Pjax::begin([]); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'passwordForm',
        'action' => Url::to(['/profile/change-password']),
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
        <?= $form->field($modelPassword, 'password_old', ['parts' => ['{font-awesome}' => 'lock']])
            ->passwordInput(['placeholder' => 'Старый пароль']) ?>

        <?= $form->field($modelPassword, 'password', ['parts' => ['{font-awesome}' => 'lock']])
            ->passwordInput(['placeholder' => 'Новый пароль'])
            ->label('Новый пароль') ?>

        <?= $form->field($modelPassword, 'confirm_password', [
            'parts' => ['{font-awesome}' => 'lock']])->passwordInput(['placeholder' => 'Повторите пароль'])
            ->label('Повторите новый пароль') ?>
        <div class="col-md-12" style="display: block; padding-top: 30px;"></div>
        <div class="form-group">
            <div class="col-lg-12 text-right">
                <?= Html::submitButton(Yii::t('app', 'Изменить пароль'), ['class' => 'btn btn-danger', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
