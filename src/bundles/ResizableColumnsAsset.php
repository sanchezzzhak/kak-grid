<?php
namespace kak\widgets\grid\bundles;
use yii\web\AssetBundle;

class ResizableColumnsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery-resizable-columns/dist';
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