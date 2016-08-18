<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Управление пользователями';
?>

<div class="row" style=" margin-bottom: 32px;">
    <div class="col-lg-6">
        <h1>Список пользователей</h1>
    </div>
</div>


<div class="col-lg-12">
    <?php
        echo \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
           // 'filterModel' => $searchModel,
            'columns' => [
                'user_id' => [
                    'label' => 'Пользователь',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->getUserLogin(), Url::toRoute(['profile/user', 'id'=>$model->user_id]));
                    }
                ],
                'photo' => [
                    'label' => 'Фото',
                    'format' => 'raw',
                    'value' => function($model) {
                         return $model->getAvatar();
                    }
                ],
                'avatar_moderation' => [
                    'label' => 'Статус',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->getModerationButtons();
                    }
                ]
//                'status'=>[
//                    'attribute'=>'status',
//                    'value' => function($model) {
//                        return $model->getStatus();
//                    }
//                ],
            ],
            'emptyText' => 'Результатов не найдено',
        ]);

    ?>
</div>


