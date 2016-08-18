<?php

use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'home');

if (!Yii::$app->user->isGuest) {
    //d(Yii::$app->user->identity);
}
?>
<?= BootstrapNotify::widget() ?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Найди свое занятие</h1>
        <p class="lead">С нами Вы сможете найти компанию людей, которые готовы поддержать вашу идею.</p>
        <p>Мы - это не только сервис для поиска занятий, мы - это объединение людей с одной целью!</p>
        <p><a class="btn btn-primary btn" href="<?=Url::to(['/event/search'])?>"><?=Yii::t('app','search')?></a></p>
    </div>
</div>