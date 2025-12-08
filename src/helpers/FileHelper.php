<?php

namespace kak\widgets\grid\helpers;

use Yii;

/**
 * @class FileHelper
 * @package kak\widgets\grid\helpers
 */
class FileHelper
{
    /**
     * Builds the name of export file.
     * @param int $userId
     * @param string $type
     * @param string $salt
     * @return string
     */
    public static function buildFileName($userId, $type, $salt)
    {
        return sprintf(
            'export-%d-%s.%s',
            $userId,
            $salt,
            $type,
        );
    }

    /**
     * Builds the full path to export file.
     * @param $fileName
     * @return mixed
     */
    public static function buildFilePath($fileName)
    {
        return Yii::getAlias(sprintf('@runtime/export/%s', $fileName));
    }
}
