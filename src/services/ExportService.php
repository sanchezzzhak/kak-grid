<?php
namespace  kak\widgets\grid\services;

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
    public $type;
    public $exportColumns;


    public function run()
    {
        try {
            $writer = $this->getWriter();
        }catch (\Exception $e){
            throw new HttpException(403, $e->getMessage());
        }

        /** @var BaseDataProvider $dataProvider */
        $dataProvider = $this->grid->dataProvider;
        $mapper = new ColumnMapper($this->grid->columns, $this->exportColumns, true);
        $source = new SourceIterator(new DataProviderBatchIterator($dataProvider, $mapper));

        $this->clearBuffers();
        $writer->openToBrowser($this->getFileName());

//        if ($model !== null && !in_array($type, [TypeHelper::JSON, TypeHelper::XML])) {
//            $writer->addRow($mapper->getHeaders($model));
//        }
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
        $type = $this->type;
        return Yii::$app->controller->id . '-' . Yii::$app->controller->action->id  . '-' . date('Y-m-d-Hi') . '.' . $type;
    }


    protected function getWriter()
    {
        return writer\WriterFactory::create($this->type);
    }

    /**
     * Clean (erase) the output buffers and turns off output buffering
     */
    protected function clearBuffers()
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

}