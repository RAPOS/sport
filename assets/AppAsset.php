<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        //'css/style.css',
        'css/main.css',
        'css/menu.css',
        'css/forms.css',
        'css/fonts.css',
        'css/error.css',
        'css/profile.css',
        'css/items.css'
    ];
    public $js = [
        //'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        $this->registerJs();
        parent::init();
    }

    protected function registerJs()
    {
        $js = <<<SCRIPT
            $("input.slider").slider();
SCRIPT;
        \Yii::$app->view->registerJs($js);
        return $this;
    }
}
