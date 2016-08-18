<?php

/* @var $model app\models\Blog */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="col-lg-12">
    <div class="block">
        <div class="block-title">
            <?= Html::encode($model->title) ?>
            <small class="info pull-right"><?= date('d.m.Y', $model->date) ?></small>
        </div>
        <div class="block-menu">
            <div class="row null">
                <div class="col-lg-12">
                    <?= HtmlPurifier::process($model->text) ?>
                </div>
            </div>
        </div>
    </div>
</div>