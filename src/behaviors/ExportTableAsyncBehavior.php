<?php

namespace kak\widgets\grid\behaviors;

use kak\widgets\grid\bundles\ExportTableAsyncAsset;
use kak\widgets\grid\helpers\ExportHelper;
use kak\widgets\grid\jobs\ExportTableAsyncJob;
use Yii;
use yii\base\Behavior;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Html;
use yii\bootstrap\Progress;
use yii\queue\JobInterface;
use yii\queue\Queue;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @class ExportTableAsyncBehavior
 * @package kak\widgets\grid\behaviors
 */
class ExportTableAsyncBehavior extends base\BaseExportTableBehavior
{
    /**
     * HTML options for dropdown list.
     * @var array
     */
    public $dropDownOptions = [
        'class' => 'export-btn',
    ];

    /**
     * Need (or not) to show the column header.
     * @var bool
     */
    public $columnHeader = true;

    /**
     * List of columns to export.
     * Exports all columns when empty.
     * @var array
     */
    public $exportColumns = [];

    /**
     * Number of rows to export.
     * Exports all rows when null.
     * @var int|null
     */
    public $limit = null;

    /**
     * Dropdown menu label.
     * @var string
     */
    public $label = '<i class="glyphicon glyphicon-export"></i> Export';

    /**
     * Queue to push the export job.
     * @var Queue
     */
    public $queue;

    /**
     * Job to push to the queue.
     * @var ExportTableAsyncJobInterface
     */
    public $job;

    /**
     * Renders the output.
     */
    public function renderExportTable()
    {
        $view = $this->owner->getView();
        ExportTableAsyncAsset::register($view);

        return $this->initButtonDropdown();
    }

    /**
     * Initializes button dropdown.
     * @return string
     */
    public function initButtonDropdown()
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
    protected function process()
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
    protected function startJob()
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
    protected function checkStatus()
    {
        $userId = Yii::$app->user->id;
        $type = Yii::$app->request->post('type');
        $salt = Yii::$app->request->post('salt');

        $fileName = ExportHelper::buildFileName($userId, $type, $salt);
        $filePath = ExportHelper::buildFilePath($fileName);
        $result = file_exists($filePath);

        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = ['status' => $result];
        Yii::$app->response->send();
    }

    /**
     * Downloads export file.
     * @throws NotFoundHttpException
     */
    protected function downloadFile()
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
