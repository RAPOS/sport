<?php

/* @var $reviews app\models\Comments */

use yii\helpers\Url;

?>

<div class="statistic">
    <div class="block">
        <div class="arrow-left"><i class="fa fa-arrow-circle-left"></i></div>
        <div class="block-title">Последние отзывы</div>
        <div class="block-menu">
            <div class="mini-review">
                <div class="media">
                    <?php if(empty($reviews)): ?>
                        <div class="media-body">
                            <p>Об этом пользователе еще нет отзывов =(</p>
                        </div>
                    <?php else: ?>
                    <?php foreach($reviews as $one): ?>
                        <div class="media-body" style="text-align: left">
                            <p>
                                <a href="<?=Url::toRoute(['/profile/user', 'id' => $one->user->id])?>">
                                    <strong><?=$one->user->last_name?> <?=$one->user->first_name?></strong>
                                </a>
                                <small>(<?=$one->getDate()?>)</small>
                                <span data-original-title="Оценка: <?=$one->getRatingTypeForUser()?>" data-toggle="tooltip">
                                    <i class="fa fa-circle <?= ($one->rating == 1) ? 'good' : (($one->rating == 2) ? 'fine' : 'bad') ?>"></i>
                                </span>
                            </p>
                            <p><?=$one->comment?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
