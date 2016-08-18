<?php
use yii\helpers\Url;
?>

<?php if ($model->status == $type): ?>
    <div class="row event-child">
        <div class="col-lg-12">
            <div class="block">
                <div class="block-title"><a href="<?= Url::toRoute('/event/event?id='. $model->event->id) ?>">Хокей</a></div>
                <div class="block-menu">
                    <div class="row">
                        <div class="col-lg-8">
                            <p>Когда? - <?=$model->event->getDateStart()?></p>
                            <p>Где? -
                                <strong>г. <?=$model->event->place->netCity->name_ru?>, ул. <?=$model->event->place->adress ?> (<?=$model->event->place->name?>)</strong>
                                <?php if($model->event->place->status != 0): ?>
                                    <span data-toggle="tooltip" title="<?=Yii::t('app','place_confirmed')?>"><i class="fa fa-check"></i></span>
                                <?php else: ?>
                                    <span data-toggle="tooltip" title="<?=Yii::t('app','place_not_confirmed')?>"><i class="fa fa-times"></i></span>
                                <?php endif; ?>
                                <span data-toggle="tooltip" title="<?=Yii::t('app','show_place_in_map')?>">
                                    <i class="showInMap fa fa-map-marker" data-address="<?=$model->event->netCity->netCountry->name_ru?> <?=$model->event->netCity->name_ru?> <?=$model->event->place->adress?>"></i>
                                </span>
                            </p>
                            <div class="author-info">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="<?= Url::toRoute('/profile/user/?id='. $model->event->user->id) ?>">
                                            <img class="media-object ava-64" src="<?=$model->event->user->getAvatar("small")?>" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="<?=Url::toRoute('/profile/user/?id='. $model->event->user->id)?>">
                                                <?=$model->event->user->last_name?> <?=$model->event->user->first_name?>
                                            </a>
                                        </h4>
                                        <?php $userRating = $model->event->user->getRating();
                                            if ($userRating):
                                        ?>
                                            <span data-original-title="Рейтинг пользователя" data-toggle="tooltip">
                                                <span class="rating-author" data-score="<?=$userRating?>"></span>
                                            </span><br>
                                        <?php endif; ?>

                                        <?php if($model->event->date_start < date("Y-m-d H:i:s") AND !\app\models\Comments::isWriteCommentYet($model->event->user->id,2)): ?>
                                            <small class="write_comment" data-id="<?=$model->event->user->id?>"><a style="cursor: pointer"><i class="fa fa-pencil-square-o"></i> Написать отзыв об авторе события</a></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-right">
                            <h4 class="alert-link"><?=$model->event->price?> руб.<br><small>с учасника</small></h4>
                            <?php if($model->event->date_start > date("Y-m-d H:i:s")): ?>
                                <p><em>уже идет <strong><?=$model->event->getCountMembers()?></strong> чел.</em></p>
                            <?php endif; ?>
                            <p>
                                <strong><?=$model->event->free_count_place?></strong> мест осталось
                                <span data-original-title="Мин. мест - <?=$model->event->min_count_place?> Макс. мест - <?=$model->event->max_count_place?>" data-toggle="tooltip" title="">
                                    <span class="glyphicon glyphicon-question-sign"></span>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>