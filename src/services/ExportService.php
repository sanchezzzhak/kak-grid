<?php
namespace  kak\widgets\grid\services;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\CSV\Writer as CsvWriter;
use Box\Spout\Writer\WriterInterface;
use kak\widgets\grid\helpers\ExportHelper;
use kak\widgets\grid\interfaces\ExportType;
use kak\widgets\grid\iterators\DataProviderBatchIterator;
use kak\widgets\grid\iterators\SourceIterator;
use kak\widgets\grid\mappers\ColumnMapper;
use Yii;
use yii\data\BaseDataProvider;
use yii\web\HttpException;

/**
 * Class ExportService
 * @package kak\widgets\grid\services
 */
class ExportService
{
    /***
     * @var \kak\widgets\grid\GridView;
     */
    public $grid;
    /**
     * @var string export type
     */
    public $type;
    /**
     * @var array set columns export default export all
     */
    public $exportColumns;
    /**
     * @var bool remove cell html
     */
    public $columnRemoveHtml = true;
    /**
     * @var null|boolean export columns named ?
     */
    public $columnHeader = null;
    /**
     * @var null|integer max page export default all
     */
    public $limit = null;

    /** @var string */
    public $csvFieldDelimiter = ';';

    public $fileName = '';

    public function run()
    {
        try {
            $writer = $this->getWriter();
        }catch (\Exception $e){
            throw new HttpException(403, $e->getMessage());
        }

        $this->initColumnHeaderNamed();

        /** @var BaseDataProvider $dataProvider */
        $dataProvider = $this->grid->dataProvider;

        $mapper = new ColumnMapper($this->grid->columns, $this->exportColumns, $this->columnRemoveHtml, $this->columnHeader, $this->type);
        $iterator = new DataProviderBatchIterator($dataProvider, $mapper, $this->limit);
        $source = new SourceIterator($iterator);

        $total = $iterator->count();
        $processed = 0;
        $progressStep = max(1, (int)ceil($total / 100));

        $this->openWriter($writer);

        if (!in_array($this->type, [ExportType::JSON_ROW,ExportType::JSON, ExportType::XML])) {
            $writer->addRow($mapper->getHeaders());
        }

        foreach ($source as $data) {
            if ($writer instanceof writer\KeyValueDataWriterInterface) {
                $writer->addRowDataToWriter($data);
            } else {
                $cells = [];

                foreach ($data as $key => $value) {
                    $cells[$key] = new Cell($value);
                }

                $row = new Row($cells, null);
                $writer->addRow($row);
            }

            $processed++;

            if (
                $this->fileName !== ''
                &&
                ($processed % $progressStep === 0 || $processed === $total)
            ) {
                $percent = $total > 0 ? (int)floor($processed / $total * 100) : 100;

                ExportHelper::saveProgress($this->fileName, 'processing', min($percent, 99));
            }
        }

        $this->closeWriter($writer);

        if ($this->fileName !== '') {
            ExportHelper::deleteProgress($this->fileName);
        }
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        $types = [
            ExportType::JSON_ROW => 'json',
        ];
        $type = isset($types[$this->type]) ? $types[$this->type] : $this->type;
        return Yii::$app->controller->id . '-' . Yii::$app->controller->action->id  . '-' . date('Y-m-d-Hi') . '.' . $type;
    }

    /**
     * Creates a new writer.
     * @return WriterInterface
     */
    protected function getWriter()
    {
        $result = writer\WriterFactory::create($this->type);
        if ($result instanceof CsvWriter) {
            $result->setFieldDelimiter($this->csvFieldDelimiter);
        }
        return $result;
    }

    /**
     * Opens the writer to file or browser.
     * @param WriterInterface $writer
     */
    public function openWriter($writer)
    {
        if ($this->fileName !== '') {
            $fileName = sprintf('%s.part', $this->fileName);
            $filePath = ExportHelper::buildFilePath($fileName);
            $writer->openToFile($filePath);
        } else {
            $writer->openToBrowser($this->getFileName());
        }
    }

    /**
     * Closing writer properly.
     * @param WriterInterface $writer
     */
    public function closeWriter($writer)
    {
        $writer->close();

        if ($this->fileName !== '') {
            $fileName = sprintf('%s.part', $this->fileName);
            $filePath = ExportHelper::buildFilePath($fileName);
            $newFilePath = ExportHelper::buildFilePath($this->fileName);
            rename($filePath, $newFilePath);
        }
    }

    protected function initColumnHeaderNamed()
    {
        if(in_array($this->type,[ExportType::JSON_ROW,ExportType::JSON, ExportType::XML])){
            $this->columnHeader = false;
        }else if($this->columnHeader === null){
            $this->columnHeader = true;
        }
    }

}
