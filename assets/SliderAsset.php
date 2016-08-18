<?php
/**
 * Date: 29.07.15
 * Time: 16:50
 */

namespace app\assets;

use yii\web\AssetBundle;

class SliderAsset extends AssetBundle
{
    public $sourcePath = '@bower/seiyria-bootstrap-slider';
    public $js = [
        'dist/bootstrap-slider.min.js'
    ];
    public $css = [
        'dist/css/bootstrap-slider.min.css'
    ];
}