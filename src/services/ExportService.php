<?php
namespace  kak\widgets\grid\services;

use kak\widgets\grid\iterators\DataProviderBatchIterator;
use kak\widgets\grid\iterators\SourceIterator;
use kak\widgets\grid\mappers\ColumnMapper;
use yii\data\BaseDataProvider;

use Yii;
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
        /** @var BaseDataProvider $dataProvider */
        $dataProvider = $this->grid->dataProvider;
        $mapper = new ColumnMapper($this->grid->columns, $this->exportColumns, true);
        $source = new SourceIterator(new DataProviderBatchIterator($dataProvider, $mapper));

        $write = $this->getWriter();
        foreach ($source as $data){
        
        }
        \Yii::$app->end();
    }


    protected function getWriter()
    {

        return null;
    }

}