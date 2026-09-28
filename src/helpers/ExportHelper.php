<?php

namespace kak\widgets\grid\helpers;

use Yii;
use yii\helpers\Json;

/**
 * @class FileHelper
 * @package kak\widgets\grid\helpers
 */
class ExportHelper
{
    private const EXPORT_DIRECTORY = '@runtime/export';

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
            'export-%d%d.%s',
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
        return Yii::getAlias(sprintf('%s/%s', self::EXPORT_DIRECTORY, $fileName));
    }

    /**
     * Build the full path to export progress file
     * @param $fileName
     * @return mixed
     */
    public static function buildProgressFilePath($fileName)
    {
        return self::buildFilePath(sprintf('%s.progress', $fileName));
    }

    /**
     * Save export progress
     * @param string $fileName
     * @param string $state
     * @param int $percent
     * @return void
     */
    public static function saveProgress(string $fileName, string $state, int $percent = 0): void
    {
        $filePath = self::buildProgressFilePath($fileName);
        $tmpFilePath = sprintf('%s.tmp', $filePath);

        file_put_contents(
            $tmpFilePath,
            Json::encode(['state' => $state, 'percent' => max(0, min($percent, 100))])
        );

        rename($tmpFilePath, $filePath);
    }

    /**
     * Get export progress
     * @param string $fileName
     * @return array
     */
    public static function getProgress(string $fileName): array
    {
        $filePath = self::buildProgressFilePath($fileName);
        $progress = [];

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);

            if ($content !== false) {
                $progress = Json::decode($content);
            }
        }

        if (!is_array($progress)) {
            $progress = [];
        }

        return [
            'state' => $progress['state'] ?? 'queued',
            'percent' => (int)($progress['percent'] ?? 0),
        ];
    }

    /**
     * Delete export progress file.
     * @param string $fileName
     * @return void
     */
    public static function deleteProgress(string $fileName): void
    {
        $filePath = self::buildProgressFilePath($fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Gets export directory.
     * @return string
     */
    public static function directory()
    {
        return Yii::getAlias(self::EXPORT_DIRECTORY);
    }
}
