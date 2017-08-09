<?php

\yii\helpers\FileHelper::createDirectory(\Yii::getAlias('@tests/_output/assets'));

return [
    'id' => 'test-kak-grid-console',
    'class' => 'yii\web\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
            'scriptFile' => \Yii::getAlias('@tests/_output/index.php'),
            'url' => '/',
            'scriptUrl' => '/index.php',
        ],
        'assetManager' => [
            'bundles' => [
                'kak\widgets\grid\GridViewAsset' => false,
                'yii\grid\GridViewAsset' => false,
                'yii\web\JqueryAsset' => false,
            ],
        ],
    ],
];