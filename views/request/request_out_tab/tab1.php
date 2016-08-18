<div id="req_tab1">
    <div class="margin-bottom"></div>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/request/_requestOutEvent',
        'layout' => "{items}\n{pager}",
        'options' => [
            'tag' => 'table',
        ],
        'viewParams' => [
            'type' => 0
        ],
        'itemOptions' => [
            'tag' => false,
        ],
    ]); ?>
</div>
