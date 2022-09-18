<?php

namespace Ryssbowh\Activity\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ActivityAssets extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $js = [
        'js/activity.js'
    ];

    public $css = [
        'css/activity.css'
    ];

    public $depends = [
        CpAsset::class,
    ];
}