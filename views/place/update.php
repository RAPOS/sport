<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Place */

$this->title = Yii::t('app', 'Изменить событие');
?>
<div class="place-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
