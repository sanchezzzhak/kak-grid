<?php

namespace kak\widgets\grid\bundles;

use yii\web\AssetBundle;

/**
 * Class GridViewAsset
 * @package kak\widgets\grid
 */
class MinColumnAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kak/grid/assets';

    public $js = [
        'min-column.js'
    ];

    public $css = [
        'min-column.css'
    ];
} 
