<?php
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div id="req_tab3">
    <div class="margin-bottom"></div>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/request/_requestEvent',
        'layout' => "{items}\n{pager}",
        'options' => [
            'tag' => 'table',
        ],
        'viewParams' => [
            'type' => 2
        ],
        'itemOptions' => [
            'tag' => false,
        ],
    ]); ?>
</div>
