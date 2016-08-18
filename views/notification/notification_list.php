<?php
/** @var $notification \app\models\Notification */

$this->title = 'Уведомления';
?>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-12">
        <?php if(empty($notification)): ?>
            <div class="block">
                <div class="block-menu">
                    <h4 class="text-center">
                        <small>У вас нет уведомлений</small>
                    </h4>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($notification as $one): ?>
                <div class="block">
                    <div class="block-title">
                        <?=$one->getType()?>
                        <small class="info pull-right"><?=$one->date?></small>
                    </div>
                    <div class="block-menu">
                        <p><?=$one->text?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

<!--        <div class="block">-->
<!--            <div class="block-title">-->
<!--                Вы приняты на событие-->
<!--                <small class="info pull-right">11.12.2015</small>-->
<!--            </div>-->
<!--            <div class="block-menu">-->
<!--                <p>Хотим сообщить Вам радосную новость. Вы были приняты на <a href="#">событие</a>, которое пройдет 11.12.2015 в <a href="#">ФОК</a>, что по адресу <a href="#">Украина г. Черновцы, ул. Сагайдачного 33</a></p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="block">-->
<!--            <div class="block-title">-->
<!--                Напоминание о запланированом событе-->
<!--                <small class="info pull-right">11.12.2015</small>-->
<!--            </div>-->
<!--            <div class="block-menu">-->
<!--                <p>Уважаемый Игорь, напоминаем Вам, что уже через 3 часа, у Вас запланированное <a href="#">событие</a></p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="block">-->
<!--            <div class="block-title">-->
<!--                Уведомления по вашем предпочитаниям-->
<!--                <small class="info pull-right">11.12.2015</small>-->
<!--            </div>-->
<!--            <div class="block-menu">-->
<!--                <p><strong>Мы подобрали для Вас новые события, по Вашим предпочитаниям:</strong></p>-->
<!--                <p>Сегодня, о 21:00 всего за <strong>150 руб.</strong> можно пойти еще с <em>4 чел.</em> на <a href="#">Футбол</a> в <a href="#">"ФОК"</a>, что по адресу <a href="#">Украина г. Черновцы, ул. Сагайдачного 33</a></p>-->
<!--                <p>Завтра, о 21:00 всего за <strong>150 руб.</strong> можно пойти еще с <em>4 чел.</em> на <a href="#">Футбол</a> в <a href="#">"ФОК"</a>, что по адресу <a href="#">Украина г. Черновцы, ул. Сагайдачного 33</a></p>-->
<!--                <p>11.12.2015, о 21:00 всего за <strong>150 руб.</strong> можно пойти еще с <em>4 чел.</em> на <a href="#">Футбол</a> в <a href="#">"ФОК"</a>, что по адресу <a href="#">Украина г. Черновцы, ул. Сагайдачного 33</a></p>-->
<!--            </div>-->
<!--        </div>-->

    </div>
</div>