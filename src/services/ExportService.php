<?php
namespace  kak\widgets\grid\services;

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

        $mapper = new ColumnMapper($this->grid->columns, $this->exportColumns, $this->columnRemoveHtml, $this->columnHeader);
        $source = new SourceIterator(new DataProviderBatchIterator($dataProvider, $mapper, $this->limit));

        $writer->openToBrowser($this->getFileName());

        if (!in_array($this->type, [ExportType::JSON_ROW,ExportType::JSON, ExportType::XML])) {
            $writer->addRow($mapper->getHeaders());
        }
        foreach ($source as $data){
            $writer->addRow($data);
        }
        $writer->close();
        \Yii::$app->end();
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


    protected function getWriter()
    {
        return writer\WriterFactory::create($this->type);
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