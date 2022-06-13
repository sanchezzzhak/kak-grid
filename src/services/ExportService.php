<?php

namespace kak\widgets\grid\services;

use Box\Spout\Writer\CSV\Writer as CsvWriter;
use kak\widgets\grid\GridView;
use kak\widgets\grid\interfaces\ExportType;
use kak\widgets\grid\iterators\DataProviderBatchIterator;
use kak\widgets\grid\iterators\SourceIterator;
use kak\widgets\grid\mappers\ColumnMapper;
use yii\data\BaseDataProvider;
use Yii;
use yii\web\HttpException;

/**
 * Class ExportService
 * @package kak\widgets\grid\services
 */
class ExportService
{
    /**** @var GridView; */
    public $grid;
    /**
     * @var string export type
     */
    public $type;
    /*** @var array set columns export default export all */
    public $exportColumns;
    /*** @var bool remove cell html */
    public $columnRemoveHtml = true;
    /*** @var null|boolean export columns named ? */
    public $columnHeader;
    /*** @var null|integer max page export default all */
    public $limit;

    /** @var string */
    public $csvFieldDelimiter = ';';

    public function run()
    {
        try {
            $writer = $this->getWriter();
        } catch (\Exception $e) {
            throw new HttpException(403, $e->getMessage());
        }

        $this->initColumnHeaderNamed();

        /** @var BaseDataProvider $dataProvider */
        $dataProvider = $this->grid->dataProvider;

        $mapper = new ColumnMapper($this->grid->columns, $this->exportColumns, $this->columnRemoveHtml, $this->columnHeader);
        $source = new SourceIterator(new DataProviderBatchIterator($dataProvider, $mapper, $this->limit));

        $writer->openToBrowser($this->getFileName());

        if (!$this->hasColumnHeaders()) {
            $writer->addRow($mapper->getHeaders());
        }

        foreach ($source as $data) {
            $writer->addRow($data);
        }

        $writer->close();
        exit;
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        $types = [
            ExportType::JSON_ROW => 'json',
        ];

        return sprintf('%s-%s-%s.%s',
            Yii::$app->controller->id,
            Yii::$app->controller->action->id,
            date('Y-m-d-Hi'),
            $types[$this->type] ?? $this->type
        );
    }

    protected function getWriter()
    {
        $result = writer\WriterFactory::create($this->type);

        if ($result instanceof CsvWriter) {
            $result->setFieldDelimiter($this->csvFieldDelimiter);
        }

        return $result;
    }

    protected function hasColumnHeaders()
    {
        return in_array($this->type, [ExportType::JSON_ROW, ExportType::JSON, ExportType::XML], false);
    }

    protected function initColumnHeaderNamed()
    {
        if ($this->columnHeader === null) {
            $this->columnHeader = true;
        }

        if ($this->hasColumnHeaders()) {
            $this->columnHeader = false;
        }
    }

}