<?php
use yii\web\Application;
use yii\web\AssetManager;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

(new Application([
    'id' => 'kak/grid',
    'name' => 'demos',
    'bootstrap' => [
        'log'
    ],
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'site/index',
    'controllerPath' => __DIR__ . '/controllers',
    'viewPath' => __DIR__ . '/views',
    'vendorPath' => __DIR__ . '../vendor',
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ]
    ]
]))->run();
