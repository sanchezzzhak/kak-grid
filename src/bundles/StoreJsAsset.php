<?php
namespace kak\widgets\grid\bundles;
use yii\web\AssetBundle;

class StoreJsAsset extends AssetBundle
{
    public $sourcePath = '@bower/store.js/dist';
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $js = [
        'store.legacy.min.js'
    ];
}
