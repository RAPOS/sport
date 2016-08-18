<?php

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

/** @var $user \app\models\User */
/** @var $profilePhoto \app\models\UserProfile */
/** @var $cities \app\models\NetCity */
/** @var $countries \app\models\NetCountry */
/** @var $plalceList \app\models\Place */
/** @var $listCities \app\models\NetCity */
/** @var $mChangePass \app\models\ChangePassForm */
/** @var $mAdressProfile \app\models\AddressProfile */
/** @var $sportPrefer \app\models\Preferences */
/** @var $placePrefer \app\models\Preferences */
/** @var $selected_country array */
/** @var $selected_city array */

/* @var $model \app\models\UserProfile */
/* @var $modelPassword \app\models\ChangePassword */

use phpnt\cropper\ImageLoadWidget;

$this->title = Yii::t('app', 'Мой профиль');
$user = Yii::$app->user->identity;
?>
    <div class="row">
    <div class="col-md-3" style="margin-top: 10px;">
        <div style="text-align: center">
            <?= ImageLoadWidget::widget([
                'id' => 'load-user-avatar',                                     // суффикс ID
                'object_id' => $user->id,                                       // ID объекта
                'imagesObject' => $user->photos,                                // уже загруженные изображения
                'images_num' => 1,                                              // максимальное количество изображений
                'images_label' => $user->avatar_label,                          // метка для изображения
                'imageSmallWidth' => 750,                                       // ширина миниатюры
                'imageSmallHeight' => 750,                                      // высота миниатюры
                'imagePath' => '/uploads/avatars/',                             // путь, куда будут записыватся изображения относительно алиаса
                'noImage' => 2,                                                 // 1 - no-logo, 2 - no-avatar, 3 - no-img или путь к другой картинке
                'classesWidget'       => [
                    'imageClass' => 'imageLoaderClass',
                    'buttonDeleteClass' => 'btn btn-xs btn-danger btn-imageDelete glyphicon glyphicon-trash glyphicon',
                    'imageContainerClass' => 'col-md-12',
                    'formImagesContainerClass' => 'formImageContainer',
                ],
                'pluginOptions' => [                                            // настройки плагина
                    'aspectRatio' => 1/1,                                       // установите соотношение сторон рамки обрезки. По умолчанию свободное отношение.
                    'strict' => false,                                          // true - рамка не может вызодить за холст, false - может
                    'guides' => true,                                           // показывать пунктирные линии в рамке
                    'center' => true,                                           // показывать центр в рамке изображения изображения
                    'autoCrop' => true,                                         // показывать рамку обрезки при загрузке
                    'autoCropArea' => 0.5,                                      // площидь рамки на холсте изображения при autoCrop (1 = 100% - 0 - 0%)
                    'dragCrop' => true,                                         // создание новой рамки при клики в свободное место хоста (false - нельзя)
                    'movable' => true,                                          // перемещать изображение холста (false - нельзя)
                    'rotatable' => true,                                        // позволяет вращать изображение
                    'scalable' => true,                                         // мастабирование изображения
                    'zoomable' => false,
                ]]);
            ?>
        </div>
    </div>
    <div class="col-md-9" style="margin-top: 40px; margin-bottom: 40px;">
        <?php
        if (Yii::$app->user->can('office')):
            ?>
            <?= $this->render('_office-profile.php', ['model' => $model, 'modelPassword' => $modelPassword]) ?>
            <?php
        else:
            ?>
            <?= $this->render('user-profile', ['model' => $model, 'modelPassword' => $modelPassword]) ?>
        <?php
        endif;
        ?>

    </div>
    </div>
    <div class="margin-bottom"></div>
<?php
$items = [
    /*[
        'label' => 'Фото профиля',
        'content' => $this->render('_tab_photo', [
            'user' => $user,
            'profilePhoto' => $profilePhoto
        ]),
        'active' => isset($_GET['status']) ? false : true,
    ],
    [
        'label' => 'Личные данные',
        'content' => $this->render('_tab_personal_data', [
            'model' => $user
        ]),
        'headerOptions' => [],
        'options' => ['id' => 'myveryownID'],
    ],
    [
        'label' => 'Мой адрес',
        'content' => $this->render('_tab_address', [
            'mAdressProfile' => $mAdressProfile,
            'countries' => $countries,
            'listCities' => $listCities
        ]),
        'headerOptions' => [],
    ],
    [
        'label' => 'Пароль',
        'content' => $this->render('_tab_change_pass', [
            'mChangePass' => $mChangePass
        ]),
        'headerOptions' => [],
    ],
    [
        'label' => 'Предпочтения',
        'content' => $this->render('_tab_preferences',[
            'countries' => $countries,
            'cities' => $cities,
            'plalceList' => $plalceList,
            'selected_country' => $selected_country,
            'selected_city' => $selected_city,
            'placePrefer' => $placePrefer,
            'sportPrefer' => $sportPrefer,
            'user' => $user
        ]),
    ],
    [
        'label' => 'Надежность',
        'content' => $this->render('_tab_reliability',[
            'user' => $user
        ]),
        'active' => isset($_GET['status']) ? true : false,
        'headerOptions' => [],
    ],*/
];
?>

<?/*= Tabs::widget([
    'navType' => 'nav-pills',
    'options' => [
        'class' => 'nav-justified nav-user-menu',
    ],
    'items' => $items
]);*/

