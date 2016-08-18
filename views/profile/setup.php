<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 07.08.2016
 * Time: 14:29
 */
/* @var $this yii\web\View */
/* @var $modelMailingForm \app\models\MailingForm */
$this->title = Yii::t('app', 'Предпочтения');
?>
<div class="row">
    <div class="col-md-6">
        <?= $this->render('_mailing-form' , ['model' => $modelMailingForm]) ?>
    </div>
</div>
