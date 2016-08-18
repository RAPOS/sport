<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
    $this->title = Yii::t('app', 'profile');
    $this->registerCssFile('/web/css/jquery.raty.css');
    $this->registerJsFile('/web/js/jquery.raty.js');
?>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-3">
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation" class="<?=(!isset($_GET['past'])?"active":"")?>"><a href="/request/outevent"><?=Yii::t('app','current_events')?></a></li>
            <li role="presentation" class="<?=(isset($_GET['past'])?"active":"")?>"><a href="/request/outevent?past=true"><?=Yii::t('app','past_events')?></a></li>
        </ul>
    </div>
    <div class="col-lg-9">
        <div class="block margin-none">
            <div class="block-title">События на которыя я подписался</div>
            <div class="block-menu">
                <?php if(!$dataProvider->totalCount): ?>
                    <div class="row event-none">
                        <div class="col-lg-12">
                            <div class="event-none">
                                <h3 class="text-center">Нет заявок на событие.</h3>
                            </div>
                        </div>
                    </div><br>
                <?php else: ?>
                    <?= Tabs::widget([
                        'options' => [
                            'class' => 'nav-pills nav-justified nav-user-menu',
                        ],
                        'items' => [
                            [
                                'label' => 'Ожыдают ответа',
                                'content' => $this->render('request_out_tab/tab1', [
                                    'dataProvider' => $dataProvider
                                ]),
                                'active' => true
                            ],
                            [
                                'label' => 'Принятые',
                                'content' => $this->render('request_out_tab/tab2', [
                                    'dataProvider' => $dataProvider
                                ]),
                            ],
                            [
                                'label' => 'Отклоненные',
                                'content' => $this->render('request_out_tab/tab3', [
                                    'dataProvider' => $dataProvider
                                ]),
                            ],
                        ],
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php Modal::begin([
    'header' => '<h4 class="modal-title">'.Yii::t('app', 'location').'</h4>',
    'id'=>'show_place',
]); ?>
<div id="map_canvas" style="background-color: #e5e3df; overflow: hidden; position: relative; top: 0; width: 565px;"></div>
<?php Modal::end(); ?>

<?php Modal::begin([
    'header' => '<h4 class="modal-title">Напишите отзыв</h4>',
    'id'=>'comment_form',
]); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'new-comment-form',
        'enableAjaxValidation' => true,
    ]); ?>
        <?= $form->field($comment, 'comment')->textArea() ?>
        <?= $form->field($comment, 'rating')->radioList([1=>"Отлично",2=>"Нормально", 3=>"Плохо"]) ?>
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
        $('.rating-place, .rating-author, .rating-reviews').raty({ // для вывода оценок
            half: true,
            starType: 'i',
            readOnly: true,
            score: function() { // значение по умолчанию из атрибута
                return $(this).attr('data-score');
            }
        });

        $(".write_comment").click(function(){
            $("#comment_form").modal("show");
            $("#comments-object_id").val($(this).data("id"));
        })
    });
</script>