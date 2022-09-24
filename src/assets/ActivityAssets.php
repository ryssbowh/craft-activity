<?php

namespace Ryssbowh\Activity\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ActivityAssets extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__;

    /**
     * @var array
     */
    public $js = [
        'js/activity.js'
    ];

    /**
     * @var array
     */
    public $css = [
        'css/activity.css'
    ];

    /**
     * @var array
     */
    public $depends = [
        CpAsset::class,
    ];
}