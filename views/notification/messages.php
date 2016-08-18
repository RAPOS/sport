<?php use yii\helpers\Url; ?>

<?php foreach($messages as $one): ?>
    <div class="media">
        <div class="media-left">
            <a href="<?=Url::toRoute([$one->writer->getProfileLink()])?>">
                <img class="media-object ava-64" src="<?=$one->writer->getAvatar("small")?>" alt="">
            </a>
        </div>
        <div class="media-body">
            <div class="message-body pull-left">
                <a href="<?=Url::toRoute([$one->writer->getProfileLink()])?>" class="media-heading"><?=$one->writer->last_name?> <?=$one->writer->first_name?></a><br>
                <?=$one->message?>
            </div>
        </div>
    </div>
<?php endforeach; ?>