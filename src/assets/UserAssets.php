<?php

namespace Ryssbowh\Activity\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class UserAssets extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__;

    /**
     * @var array
     */
    public $css = [
        'css/activity-user.css'
    ];

    /**
     * @var array
     */
    public $depends = [
        CpAsset::class,
    ];
}