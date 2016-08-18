<?php
/* @var $this yii\web\View */
/* @var $event app\models\Event */
/* @var $comment app\models\Comments */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'profile');
?>

<div class="margin-bottom"></div>
<div class="sorting">
    <div class="row">
        <div class="col-lg-12 text-right">
            <span>Показать: </span>
            <div class="btn-group btn-group-xs" data-toggle="buttons">
                <label class="btn btn-default activeRequest active" data-type="request-all">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked>Все
                </label>
                <label class="btn btn-default activeRequest" data-type="request-actual">
                    <input type="radio" name="options" id="option2" autocomplete="off">Актуальные
                </label>
                <label class="btn btn-default activeRequest" data-type="request-received">
                    <input type="radio" name="options" id="option3" autocomplete="off">Принятые
                </label>
                <label class="btn btn-default activeRequest" data-type="request-rejected">
                    <input type="radio" name="options" id="option4" autocomplete="off">Отклоненные
                </label>
            </div>
        </div>
    </div>
</div>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-12">
        <div class="block margin-none">
            <div class="block-title"><?=Yii::t('app','request_for_event')?> #<?=$event->id?></div>
            <div class="block-menu">
                <?php if(!$dataProvider->totalCount): ?>
                    <h3 class="text-center"><?=Yii::t('app','in_you_not_request')?></h3>
                <?php else: ?>
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '/request/_itemEvent',
                        'layout' => "{items}\n{pager}",
                        'options' => [
                            'tag' => 'div',
                            'class' => 'row'
                        ],
                        'itemOptions' => [
                            'tag' => false,
                        ],
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    'header' => '<h4 class="modal-title">Напишите отзыв</h4>',
    'id'=>'comment_form',
]); ?>
<?php $form = ActiveForm::begin([
    'id' => 'new-comment-form',
    'enableAjaxValidation' => true,
]); ?>
<?= $form->field($comment, 'comment')->textArea() ?>
<?= $form->field($comment, 'rating')->radioList([1 => "Отлично", 2 => "Нормально", 3 => "Плохо", 4 => "Не пришол"]) ?>
<?= $form->field($comment, 'object_id')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-lg-12">
            <?= Html::submitButton(Yii::t('app', 'add'), ['class' => 'btn btn-primary pull-left', 'name' => 'login-button']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>



<script>
    $(document).ready(function() {
        $(".write_comment").click(function(){
            $("#comment_form").modal("show");
            $("#comments-object_id").val($(this).data("id"));
        })
    });

    $('.activeRequest').click(function() {
        $('.request-all').hide();

        if ($(this).data('type'))
            $('.' + $(this).data('type')).show();
    });
</script>
