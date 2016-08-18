<?php
use yii\helpers\Url;

/** @var $statsAll array */
/** @var $statsToday array */

$this->title = 'Админка';
?>

<div class="row">
    <div class="col-lg-6">
        <h1>Всего</h1>
    </div>
    <div class="col-lg-6">
        <h1>За сегодня</h1>
    </div>
</div>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-6">
        <table class="table table-bordered table-hover">
            <tr>
                <td>Пользователей</td>
                <td><a href="<?=Url::toRoute(['/admin/users'])?>"><?=$statsAll['user']?></a></td>
            </tr>
            <tr>
                <td>Событий</td>
                <td><a href="#"><?=$statsAll['event']?></a></td>
            </tr>
            <tr>
                <td>Подтвержденных мест</td>
                <td><a href="<?=Url::toRoute(['/admin/place'])?>"><?=$statsAll['placeAdopted']?></a></td>
            </tr>
            <tr>
                <td>Не подтвержденных мест</td>
                <td><a href="<?=Url::toRoute(['/admin/place', 'type' => 'adopted'])?>"><?=$statsAll['placeNotAdopted']?></a></td>
            </tr>
        </table>
    </div>
    <div class="col-lg-6">
        <table class="table table-bordered table-hover">
            <tr>
                <td>Пользователей</td>
                <td><?=$statsToday['user']?></td>
            </tr>
            <tr>
                <td>Событий</td>
                <td><?=$statsToday['event']?></td>
            </tr>
            <tr>
                <td>Подтвержденных мест</td>
                <td><?=$statsToday['placeAdopted']?></td>
            </tr>
            <tr>
                <td>Не подтвержденных мест</td>
                <td><?=$statsToday['placeNotAdopted']?></td>
            </tr>
        </table>
    </div>
</div>