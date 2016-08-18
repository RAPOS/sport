<?php

/** @var $user \app\models\User */
/** @var $dialogs \app\models\Dialog */
/** @var $messages \app\models\Dialog */

$this->title = Yii::t('app', 'messages');
$this->registerJsFile('/web/js/jquery.nicescroll.min.js');
?>

<script>
    var curent_dialog = '<?=$dialogs[0]->userOpponents()->id?>';
</script>

<div class="margin-bottom"></div>
<div class="row">
    <div class="col-lg-3 message-nav">
        <ul class="nav nav-pills nav-stacked" role="tablist">
            <?php
                $counter = 0;
                $arr = [];
                foreach($dialogs as $one):
                    $counter++;

                    if (in_array($one->userOpponents()->id,$arr))
                        continue;
                    else
                        $arr[] = $one->userOpponents()->id;
            ?>
                <li role="presentation" data-user="<?=$one->userOpponents()->id?>" class="one_dialog <?=$counter==1?"active":""?>">
                    <a style="cursor: pointer">
                        <img class="ava" src="<?=$one->userOpponents()->getAvatar("small")?>" alt="">
                        <strong><?=$one->userOpponents()->last_name?> <?=$one->userOpponents()->first_name?></strong>
                        <span class="badge"><?=\app\models\Dialog::getCountNoReaded($one->userOpponents()->id)?></span><br>
                        <small><i><?=Yii::t('app','messages_from_user')?></i></small>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-lg-9">
        <div class="thumbnail">
            <div class="message" id="blockForMessage">
                <?=$this->render('/notification/messages',['messages' => $messages]); ?>
            </div>
            <form id="new-message" class="new-message">
                <div class="row">
                    <div class="col-lg-10">
                        <textarea id="message-text" class="form-control" rows="2" placeholder="<?=Yii::t('app','messages_placeholder_text')?>"></textarea>
                    </div>
                    <div class="col-lg-2" style="margin-top: 8px;">
                        <input type="submit" class="btn btn-block btn-primary" value="<?=Yii::t('app','send')?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        setTimeout(function(){
            scrollToBottom();
        });

        $(".message").niceScroll({scrollspeed : 50, cursorcolor: "#2e6da4", cursorwidth : "6px", autohidemode : false});
        $('.message-nav').niceScroll({scrollspeed : 50, cursorcolor: '#2e6da4'});

        $(".one_dialog").click(function(){
            $(".one_dialog").removeClass("active");
            $(this).addClass("active");
            var user = $(this).data("user");
            curent_dialog = user;
            $.ajax({
                url : '/ajax/getdialog',
                data : {user : user},
                type : "POST",
                dataType : "html",
                success : function(e){
                    $("#blockForMessage").html(e);
                }
            })
        });

        $("#new-message").submit(function(e) {
            e.preventDefault();
            sendMessages();
        }).keyup(function(e) {
           if (e.keyCode == 13 && e.shiftKey == false) {
               e.preventDefault();
               sendMessages();
           }
        });

        function sendMessages() {
            var userId = $("#userId").val();
            var text =  $("#message-text").val();

            $.ajax({
                url : '/ajax/newmessage',
                data : {'user_id' : curent_dialog, 'text' : text},
                type : "POST",
                success : function(){
                    $("#blockForMessage").append(newMessage(text));
                    scrollToBottom();
                    $("#message-text").val("");
                }
            })
        }

        setInterval(function(){
            updateMessageDialog();
        },3000);


    });

    function newMessage(text){
        return '<div class="media no-read"><div class="media-left"><a href="<?=$user->getProfileLink()?>"><img alt="" src="<?=$user->getAvatar("small")?>" class="media-object ava-64"></a></div> <div class="media-body"><div class="message-body pull-left"><a class="media-heading" href="#"><?=$user->last_name?> <?=$user->first_name?></a><br>'+text+'</div></div></div>';
    }

    function scrollToBottom(){
        var objDiv = document.getElementById("blockForMessage");
        objDiv.scrollTop = objDiv.scrollHeight;
    }

    function updateMessageDialog(){
        var length = $(".media-body").length;
        $.ajax({
            url : '/ajax/updatemessage',
            data : {'user' : curent_dialog, 'length' : length},
            type : "POST",
            success : function(result){
                if(result != 0) {
                    $("#blockForMessage").html(result);
                    scrollToBottom();
                }
            }
        })
    }


</script>