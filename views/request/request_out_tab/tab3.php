<div id="req_tab3">
    <div class="margin-bottom"></div>
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/request/_requestOutEvent',
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
