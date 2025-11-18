<?php

namespace kak\widgets\grid\bundles;

use yii\web\AssetBundle;

/**
 * @class ExportTableAsyncAsset
 * @package kak\widgets\grid\bundles
 */
class ExportTableAsyncAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kak/grid/assets';

    public $js = [
        'kak.export-table-async.js',
    ];

    public $css = [
        'kak.export-table-async.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'kak\widgets\grid\bundles\GridViewAsset',
    ];
}
