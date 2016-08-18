<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \yii\helpers\Url;
use app\assets\AppAsset;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use nex\datepicker\DatePicker;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\widgets\MaskedInput;
use phpnt\oAuth\AuthChoice;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;

$this->title = 'Регистрация нового пользователя';

if (Yii::$app->geoData->city == 0) {
    $city = Yii::$app->geoData->setData($timezone = 'Europe/Moscow', $city = 524901, $region = 524894, $country = 185);
}
?>
<?php Pjax::begin([
    'id' => 'signupPjaxBlock'
]) ?>
<?php
AppAsset::register($this);
BootstrapSelectAsset::register($this);
AwesomeBootstrapCheckboxAsset::register($this);
?>
<div class="site-signup">
    <div class="col-md-6" style="margin-top: 30px;">
        <h1>Добро пожаловать на наш сайт!</h1>
        <?php if ($model->scenario == 'entity'): ?>
            <h3>Возможности юр. лиц</h3>
            <a class="btn btn-primary" href="<?=Url::to(['/site/signup'])?>" role="button">Регистрация для физических лиц</a>
        <?php else: ?>
            <h3>Регистрация через:</h3>
            <div style="text-align: center">
                <?= AuthChoice::widget([
                    'baseAuthUrl' => ['/auth/index'],
                ]) ?>
            </div>
            <h3>Вы представляете <span class="text-primary">организацию</span> или <span class="text-primary">спортзал</span>? Тогда вам сюда!</h3>
            <a class="btn btn-primary" href="<?=Url::to(['/site/signup', 'entity' => 1])?>" role="button">Регистрация для юридических лиц</a>
        <?php endif; ?>
    </div>
    <div class="col-md-6" style="margin-top: 40px;">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php if($model->scenario == 'entity'): ?>
                        Регистрация для юридического лица
                    <?php else: ?>
                        <?= Html::encode($this->title) ?>
                    <?php endif; ?>
                </h3>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'signupForm',
                'action' => $model->scenario == 'entity' ? Url::to(['/site/signup', 'entity' => 1]) : Url::to(['/site/signup']),
                'options' => [
                    'id' => 'signupForm',
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
                <?php if ($model->scenario == 'default'): ?>
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
                    <div class="clearfix"></div>
                    <?= $form->field($model, 'sex', ['template' => '<div class="col-md-12">{label}</div>{input}<div class="col-md-12">{error}</div>'])
                        ->radioList(
                            $model->getSexArray(),
                            [
                                'class' => 'radio radio-primary',
                                'item' => function ($index, $label, $name, $checked, $value){
                                    return '<div class="col-xs-6"><input type="radio" id="check-h-'.$index.'" name="SignupForm[sex]" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            <label for="check-h-'.$index.'">'.$label.'</label></div>';
                                }
                            ])->hint(false)->error(false); ?>
                    <div class="col-md-12">
                        <h4 style="padding-top: 15px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">Дата рождения</h4>
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
                                'defaultDate'       => Yii::$app->formatter->asDate(strtotime(time('1970/1/1')), "php:Y-m-d"),
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
                    <?= $form->field($model, 'type', [
                    'template' => '{input}'])
                    ->hiddenInput(['value' => '1'])->label(false) ?>
                    <div class="col-md-12" style="display: block; padding-top: 30px;"></div>
                <?php else: ?>
                    <?= $form->field($model, 'company_name', [
                        'template' => '<div class="col-md-4">{label}</div>
                            <div class="col-md-8">{input}</div>
                        <div class="col-md-8 col-md-offset-4">{error}</div>'])
                        ->textInput(['placeholder' => 'Название организации']) ?>
                    <div class="col-md-12">
                        <h4 style="padding-bottom: 10px; border-bottom: 1px solid #ccc;">Местоположение</h4>
                    </div>
                    <?php
                    if ($model->country == null && Yii::$app->geoData->country) {
                        $model->country = Yii::$app->geoData->country;
                    }
                    ?>
                    <?= $form->field($model, 'country', [
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
                                    data: jQuery("#signupForm").serialize(),
                                    container: "#signupPjaxBlock",
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
                                'url' => Url::to(['/ajax/search-city', 'id'=> $model->country, 'q'=>'QRY']),
                                'wildcard' => 'QRY'
                            ]
                        ]
                    ]);
                    ?>
                    <?php
                    if ($model->city_name == null && (Yii::$app->geoData->city && Yii::$app->geoData->country == $model->country)) {
                        $model->city_name = $model->getCityName();
                    }
                    ?>
                    <?= $form->field($model, 'city_name', [
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
                                        //console.log(obj);
                                        //console.log(datum.id);
                                        $("#signupform-city").val(datum.id);
                                    }'
                                ),
                            ],
                            'dataSets' => [
                                [
                                    'name' => 'city_name',
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

                    <?= $form->field($model, 'city', [
                        'template' => '{input}'])->hiddenInput()->label(false);?>

                    <?= $form->field($model, 'address', [
                        'parts' => ['{font-awesome}' => 'map-marker']])->textInput(['placeholder' => 'Адрес офиса компании'])
                        ->error(['encode' => false]) ?>
                    <?= $form->field($model, 'phoneNum', [
                        'template' => '<div class="col-md-4">{label}</div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">+'.$model->getPhoneCode($model->country).'</span>
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
                            'name' => 'phoneNum',
                            'mask' => $model->getPhoneMask($model->country)
                        ])
                        ->hint('*Номер телефона организации') ?>
                    <?= $form->field($model, 'type', [
                        'template' => '{input}'])
                        ->hiddenInput(['value' => '2'])->label(false) ?>
                <?php endif;  ?>
                <?= $form->field($model, 'email', ['parts' => ['{font-awesome}' => 'envelope-o']])
                    ->textInput(['placeholder' => 'Email'])
                    ->hint(Yii::t('app', '*Мы вышлем Вам письмо по электронной почте с ссылкой для активации учетной записи.')) ?>

                <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])
                    ->passwordInput(['placeholder' => 'Пароль']) ?>

                <?= $form->field($model, 'confirm_password', [
                    'parts' => ['{font-awesome}' => 'lock']
                ])->passwordInput(['placeholder' => 'Повторите пароль']) ?>
                <?= $form->field($model, 'agreeTerm')
                    ->checkbox([
                        'template' => '<div class="col-md-12"><div class="checkbox checkbox-primary">{input}{beginLabel}{labelTitle}{endLabel}{error}</div></div>',
                    ])
                    ->hint(false)
                    //->error(false)
                    ->label('Регистрируясь я соглашаюсь с условиями '.
                        Html::a(Yii::t('app', 'Пользовательского соглашения'),
                            ['#'],
                            [
                                'data-toggle'   => 'modal',
                                'data-target'   => '#agreeTerm',
                                'style'         => 'color: green; font-weight: 700;'
                            ]).'.'); ?>

                <?= Html::hiddenInput('model', 'app\models\SignupForm') ?>
                <?= Html::hiddenInput('scenario', $model->scenario) ?>
                <?= Html::hiddenInput('form', '@app/views/site/signup') ?>

            </div>
            <div class="box-footer text-center">
                <?= Html::submitButton(Yii::t('app', 'sing_up'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>


        <?php /*if (!isset($_GET['entity'])): */?><!--
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <p>Либо можете зарегистрироваться при помощи</p>
                        <a class="btn btn-primary" href="#" id="attached_vk"><i class="fa fa-vk"> Войти с VK</i></a> или
                        <a class="btn btn-primary" href="#" id="attached_fb"><i class="fa fa-facebook-square"></i> Войти с Facebook</a>
                    </div>
                </div>
            --><?php /*endif; */?>

    </div>
</div>
<?php Pjax::end() ?>
<?= $this->render('_agreeTerm') ?>
<script>
    /*$(document).ready(function() {
     $("#attached_fb").click(function () {
     $(".eauth-service-id-facebook a").click();
     });
     $("#attached_vk").click(function () {
     $(".eauth-service-id-vkontakte a").click();
     });

     $('input').focusin(function() {
     $('label[for='+ $(this).attr('id') +']').fadeIn('slow');
     }).focusout(function() {
     if ($(this).val() == '') {
     $('label[for=' + $(this).attr('id') + ']').fadeOut('slow');
     }
     });
     });*/
</script>