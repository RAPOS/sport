<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = Yii::t('app', 'Создать событие');
?>
<div class="event-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
