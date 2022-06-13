<?php

use Symfony\Component\String\ByteString;
use Symfony\Component\String\UnicodeString;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$autoloadGitHub = dirname(__DIR__, 1) . '/vendor/autoload.php';
$yiiGitHub = dirname(__DIR__, 1) . '/vendor/yiisoft/yii2/Yii.php';


if (!\function_exists('s')) {
    /**
     * @param string|null $string
     * @return UnicodeString|ByteString
     */
    function s(?string $string = '')
    {
        $string = (string)$string;
        return preg_match('//u', $string) ? new UnicodeString($string) : new ByteString($string);
    }
}

require_once $yiiGitHub;
require_once $autoloadGitHub;

Yii::setAlias('@tests', __DIR__);
Yii::setAlias('@data', __DIR__ . DIRECTORY_SEPARATOR . '_data');
Yii::setAlias('@vendor/kak/grid/assets', __DIR__ . '/../assets');
