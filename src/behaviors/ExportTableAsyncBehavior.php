<?php

namespace kak\widgets\grid\behaviors;

use kak\widgets\grid\behaviors\base\BaseExportTableBehavior;
use kak\widgets\grid\bundles\ExportTableAsyncAsset;
use kak\widgets\grid\helpers\ExportHelper;
use kak\widgets\grid\interfaces\ExportTableAsyncJobInterface;
use Yii;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Html;
use yii\bootstrap\Progress;
use yii\queue\Queue;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @class ExportTableAsyncBehavior
 * @package kak\widgets\grid\behaviors
 */
class ExportTableAsyncBehavior extends BaseExportTableBehavior
{
    /**
     * HTML options for dropdown list.
     */
    public array $dropDownOptions = [
        'class' => 'export-btn',
    ];

    /**
     * Need (or not) to show the column header.
     */
    public bool $columnHeader = true;

    /**
     * List of columns to export.
     * Exports all columns when empty.
     */
    public array $exportColumns = [];

    /**
     * Number of rows to export.
     * Exports all rows when null.w
     */
    public ?int $limit = null;

    /**
     * Dropdown menu label.
     */
    public string $label = '<i class="glyphicon glyphicon-export"></i> Export';

    /**
     * Queue to push the export job.
     */
    public Queue $queue;

    /**
     * Job to push to the queue.
     */
    public ExportTableAsyncJobInterface $job;

    /**
     * Renders the output.
     */
    public function renderExportTable(): string
    {
        $view = $this->owner->getView();
        ExportTableAsyncAsset::register($view);

        return $this->initButtonDropdown();
    }

    /**
     * Initializes button dropdown.
     */
    public function initButtonDropdown(): string
    {
        $this->process();

        $dropdown = ['encodeLabels' => false];
        $hash = hash('crc32', $this->owner->getId() . 'export-table');
        $dropdown['items'][] = '';

        foreach ($this->types as $type => $label) {
            $dropdown['items'][] = [
                'label' => $label,
                'url' => '?' . \Yii::$app->request->getQueryString(),
                'options' => [
                    'data-hash' => $hash,
                ],
                'linkOptions' => [
                    'data-type' => $type,
                    'class' => 'export-link-format-async',
                ],
            ];
        }

        $button = ButtonDropdown::widget([
            'encodeLabel' => false,
            'label' => $this->label,
            'dropdown' => $dropdown,
            'options' => $this->dropDownOptions,
        ]);

        $progress = Progress::widget([
            'percent' => 100,
            'options' => [
                'style' => 'display: none',
                'class' => 'export-progress',
            ],
            'barOptions' => [
                'class' => 'progress-bar-striped active',
            ],
        ]);

        return Html::tag('div', $button . $progress, [
            'class' => 'export',
        ]);
    }

    /**
     * Processes the request.
     * @throws NotFoundHttpException
     */
    protected function process(): void
    {
        if (Yii::$app->request->post('export') == 1) {
            Yii::$app->response->clearOutputBuffers();
            $this->startJob();
            exit();
        }

        if (Yii::$app->request->post('check') == 1) {
            Yii::$app->response->clearOutputBuffers();
            $this->checkStatus();
            exit();
        }

        if (Yii::$app->request->post('download') == 1) {
            Yii::$app->response->clearOutputBuffers();
            $this->downloadFile();
            exit();
        }
    }

    /**
     * Starts the export job.
     */
    protected function startJob(): void
    {
        $type = Yii::$app->request->post('type');
        $salt = Yii::$app->request->post('salt');

        $this->job->setType($type);
        $this->job->setSalt($salt);
        $this->queue->push($this->job);

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = ['status' => 'ok'];
        Yii::$app->response->send();
    }

    /**
     * Checks if export file exists.
     */
    protected function checkStatus(): void
    {
        $userId = Yii::$app->user->id;
        $type = Yii::$app->request->post('type');
        $salt = Yii::$app->request->post('salt');

        $fileName = ExportHelper::buildFileName($userId, $type, $salt);
        $filePath = ExportHelper::buildFilePath($fileName);

        if (file_exists($filePath)) {
            $result = ['status' => true, 'state' => 'ready', 'percent' => 100];
        } else {
            $progress = ExportHelper::getProgress($fileName);

            $result = [
                'status' => false,
                'state' => $progress['state'],
                'percent' => $progress['percent'],
                'message' => $progress['message']
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $result;
        Yii::$app->response->send();
    }

    /**
     * Downloads export file.
     * @throws NotFoundHttpException
     */
    protected function downloadFile(): void
    {
        $userId = Yii::$app->user->id;
        $type = Yii::$app->request->post('type');
        $salt = Yii::$app->request->post('salt');

        $fileName = ExportHelper::buildFileName($userId, $type, $salt);
        $filePath = ExportHelper::buildFilePath($fileName);

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('File not found');
        }

        Yii::$app->response->sendFile($filePath);
        Yii::$app->response->send();
    }
}
