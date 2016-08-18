<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\ListView;

$this->title = 'Блог';
?>

<h1 class="text-center"><?= $this->title ?></h1>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_itemNews',
    'layout' => "{items}\n{pager}",
    'options' => [
        'tag' => 'div',
        'class' => 'row'
    ],
    'itemOptions' => [
        'tag' => false,
    ],
]); ?>