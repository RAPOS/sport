<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Мои события');
?>
<div class="event-index">
    <section class="content">
        <p class="text-right"><?= Html::a(Yii::t('app', 'Создать событие'), ['create'], ['class' => 'btn btn-success']) ?></p>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin(['id' => 'userGridBlock']); ?>
                <?= BootstrapNotify::widget() ?>
                <div class="col-md-12 table-responsive">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => [
                            'class' => 'item col-md-4',
                            'style' => 'padding: 10px;'
                        ],
                        'summary'=>'',
                        'itemView' => function ($model, $key, $index, $widget) {                // альтернативный способ передать данные в представление
                            return $this->render('_item' ,[
                                'model' => $model,
                                'key' => $key,
                                'index' => $index,
                                'widget' => $widget
                            ]);
                        },
                    ]) ?>
                </div>

            </div>
            <?php Pjax::end(); ?>
        </div>
    </section>
</div>
