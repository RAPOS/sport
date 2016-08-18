<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Place */

$this->title = Yii::t('app', 'Добавить место');
?>
<div class="place-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
