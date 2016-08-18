<?php
use yii\helpers\Html;
use kartik\select2\Select2;
?>
<?php //echo Select2::widget([
//    'name' => 'state_12',
//    //'language' => Yii::$app->language,
//    //'data' => $data
//    'data' => [
//        'ru' => 'Russian',
//        'en' => 'English',
//    ],
//    'options' => ['placeholder' => 'Select a state ...'],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],
//]);
?>
<div id="lang">
    <div class="dropup">
        <span><?=Yii::t('app', 'choice_lang')?>:</span>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <span id="current-lang" class="<?= $current->url ?>"><?= $current->name;?></span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($langs as $lang):?>
            <li class="<?= $lang->url ?>"><?= Html::a($lang->name, '/'.$lang->url.Yii::$app->getRequest()->getLangUrl()) ?></li>
            <?php endforeach;?>
        </ul>
    </div>
</div>