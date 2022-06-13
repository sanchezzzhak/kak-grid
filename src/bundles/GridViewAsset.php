<?php

namespace kak\widgets\grid\bundles;

use yii\web\AssetBundle;

/**
 * Class GridViewAsset
 * @package kak\widgets\grid
 */
class GridViewAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kak/grid/assets';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $js = [
        'kak.grid-view.js'
    ];
    public $css = [
        'kak.grid-view.css'
    ];
} 