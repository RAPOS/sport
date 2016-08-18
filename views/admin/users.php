<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

/** @var $model \app\models\User */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Управление пользователями';
?>

<div class="row">
    <div class="col-lg-12">
        <h1>Список пользователей</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                'id',
                'login' => [
                    'label' => 'Логин',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->last_name." ".$model->first_name, Url::toRoute(['profile/user', 'id'=>$model->id]));
                    }
                ],
                'email',
                'last_name',
                'first_name',
                'sex'=>[
                    'attribute'=>'sex',
                    'value' => function($model) {
                        return $model->getSex();
                    }
                ],
                'date_reg',
                'status'=>[
                    'attribute'=>'status',
                    'value' => function($model) {
                        return $model->getStatus();
                    }
                ],

                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return '<a data-pjax="0" data-method="post" data-confirm="Вы уверены, что хотите удалить этот элемент?" aria-label="Удалить" title="Удалить" href="'.Url::toRoute(['/admin/userdelete?id='.$model->id]).'"><span class="glyphicon glyphicon-trash"></span></a>';
                        },
                        'update' => function ($url, $model) {
                            return '<a data-pjax="0" aria-label="Редактировать" title="Редактировать" href="'.Url::toRoute(['/admin/useredit?id='.$model->id]).'"><span class="glyphicon glyphicon-pencil"></span></a>';
                        },
                    ],

                ],

            ],
            'emptyText' => 'Результатов не найдено',
        ]); ?>
    </div>
</div>


