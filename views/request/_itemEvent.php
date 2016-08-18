<?php
/* @var $model app\models\Request */

$class = (($model->status == 0) ? 'request-actual' : (($model->status == 1) ? 'request-received' : 'request-rejected'));
?>

<div class="free-time request-all <?= $class ?>">
    <p><?=Yii::t('app','user')?>: <a href="<?=\yii\helpers\Url::toRoute([$model->user->getProfileLink()])?>"><?=$model->user->last_name?> <?=$model->user->first_name?></a> <?=Yii::t('app','responded_on_your_event')?></p>
    <div class="blockForRequest">
        <?php if($model->status == 0 AND $model->event->date_start > date("Y-m-d H:i:s")): ?>
            <?php if ($model->event->free_count_place > 0): ?>
                <p><?=Yii::t('app','status')?>: <strong><?=$model->getStatus()?></strong></p>
                <a class="acceptRequest" data-request_id="<?=$model->id?>" data-type="accept"><button type="button" class="btn btn-xs btn-success"><?=Yii::t('app','accept')?></button></a>
                <a class="acceptRequest" data-request_id="<?=$model->id?>" data-type="aject"><button type="button" class="btn btn-xs btn-danger"><?=Yii::t('app','reject')?></button></a>
            <?php else: ?>
                <strong>Вы не можете управлять данной заявкой, т.к. событие набрало максимум участников!</strong>
            <?php endif; ?>
        <?php else: ?>
            <?php if($model->getStatus() == 'Принят'): ?>
                <p><?=Yii::t('app','status')?>: <strong><?=$model->getStatus()?></strong></p>
                <?php if(!(($model->event->max_count_place - $model->event->free_count_place) < $model->event->min_count_place)): ?>
                    <?php if($model->event->date_start < date("Y-m-d H:i:s") AND  !\app\models\Comments::isWriteCommentYet($model->user->id, 3)): ?>
                        <p><small class="write_comment" data-id="<?=$model->user->id?>"><a style="cursor: pointer"><i class="fa fa-pencil-square-o"></i> Написать отзыв об учаснике события</a></small></p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <p><?=Yii::t('app','status')?>: <?=$model->getStatus()?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>