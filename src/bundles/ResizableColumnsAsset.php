<?php
namespace kak\widgets\grid\bundles;
use yii\web\AssetBundle;

/**
 * Class ResizableColumnsAsset
 * @package kak\widgets\grid\bundles
 */
class ResizableColumnsAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-resizable-columns/dist';
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $js = [
        'jquery.resizableColumns.js'
    ];
    public $css = [
        'jquery.resizableColumns.css'
    ];
}
