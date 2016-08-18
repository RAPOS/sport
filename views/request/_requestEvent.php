<?php if ($model->status == $type): ?>
<div class="free-time">
    <p><?=Yii::t('app','user')?>: <?=$model->user->last_name?> <?=$model->user->first_name?> <?=Yii::t('app','responded_on_your_event')?></p>
    <p><?=Yii::t('app','event_type')?>: <?=$model->event->eventType->name?></p>

    <div class="blockForRequest">
        <?php if($model->status == 0 AND $model->event->date_start > date("Y-m-d H:i:s")): ?>
            <a class="acceptRequest" data-request_id="<?=$model->id?>" data-type="accept"><button type="button" class="btn btn-xs btn-success"><?=Yii::t('app','accept')?></button></a>
            <a class="acceptRequest" data-request_id="<?=$model->id?>" data-type="aject"><button type="button" class="btn btn-xs btn-danger"><?=Yii::t('app','reject')?></button></a>
        <?php else: ?>
            <?php if($model->getStatus() == 'Принят'): ?>
                <p><?=Yii::t('app','status')?>: <strong><?=$model->getStatus()?></strong></p>
                <?php if($model->event->date_start < date("Y-m-d H:i:s") AND  !\app\models\Comments::isWriteCommentYet($model->user->id, 3)): ?>
                    <p><small class="write_comment" data-id="<?=$model->user->id?>"><a style="cursor: pointer"><i class="fa fa-pencil-square-o"></i> Написать отзыв об учаснике события</a></small></p>
                <?php endif; ?>
            <?php else: ?>
                <p><?=Yii::t('app','status')?>: <?=$model->getStatus()?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div><br>
<?php endif; ?>