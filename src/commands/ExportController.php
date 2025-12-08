<?php

namespace kak\widgets\grid\commands;

use kak\widgets\grid\helpers\ExportHelper;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Grid commands.
 * @package kak\widgets\grid\commands
 */
class ExportController extends \yii\console\Controller
{
    private const CLEANUP_INTERVAL = 3600;

    /**
     * Removes temporary files.
     */
    public function actionClean(): int
    {
        $exportDirectory = ExportHelper::directory();
        Console::output(sprintf('Cleanup direcory: %s', $exportDirectory));

        foreach (FileHelper::findFiles($exportDirectory) as $filePath) {
            $fileName = basename($filePath);
            $createdAt = filectime($filePath);

            if ($createdAt < time() - self::CLEANUP_INTERVAL) {
                unlink($filePath);
                Console::output(sprintf('Removed: %s', $fileName));
            }
        }

        Console::output('Done!');

        return ExitCode::OK;
    }
}
