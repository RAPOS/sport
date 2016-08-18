<div id="req_tab2">
    <div class="margin-bottom"></div>
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/request/_requestOutEvent',
        'layout' => "{items}\n{pager}",
        'options' => [
            'tag' => 'table',
        ],
        'itemOptions' => [
            'tag' => false,
        ],
        'viewParams' => [
            'type' => 1
        ],
    ]); ?>
</div>
