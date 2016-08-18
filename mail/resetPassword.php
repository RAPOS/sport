<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:38
 *
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<div class="password-reset">
    <p><?= Yii::t('app', 'Hello') ?></p>

    <p><?= Html::a(Yii::t('app', 'Follow the link to reset your password.'),
            Yii::$app->urlManager->createAbsoluteUrl(
                [
                    '/site/reset-password',
                    'key' => $user->secret_key
                ]
            )); ?>
    </p>
</div>
