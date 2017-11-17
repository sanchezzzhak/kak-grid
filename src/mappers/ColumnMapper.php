<?php
namespace kak\widgets\grid\mappers;


use kak\widgets\grid\columns\CheckboxColumn;
use kak\widgets\grid\columns\DataColumn;
use yii\db\ActiveRecordInterface;
use yii\grid\ActionColumn;

class ColumnMapper
{

    private $columns = [];
    private $exportColumns = [];
    private $removeHtml = true;

    public function __construct($columns, $exportColumns, $removeHtml)
    {
        $this->columns = $columns;
        $this->exportColumns = $exportColumns;
        $this->removeHtml = $removeHtml;
    }

    /**
     * @param $model
     * @param $index
     * @return array
     */
    public function map($model, $index)
    {
        $row = [];
        foreach ($this->columns as $column) {
            if ($this->isColumnExportable($column)) {
                /** @var DataColumn $column */
                $key = $model instanceof ActiveRecordInterface
                    ? $model->getPrimaryKey()
                    : $model[$column->attribute];
                $value = $this->getColumnValue($column, $model, $key, $index);
                $header = $this->getColumnHeader($column);
                $row[$header] = $value;
            }
        }

        return $row;
    }

    /**
     * @param $column DataColumn
     * @param $model
     * @param $key
     * @param $index
     * @return string
     */
    protected function getColumnValue($column, $model, $key, $index)
    {
        $value = $column->renderDataCell($model, $key, $index);
        return ($this->removeHtml) ? strip_tags($value) : $value;
    }

    /**
     * @param $column DataColumn
     * @return string
     */
    protected function getColumnHeader($column)
    {
        $value = $column->renderHeaderCell();
        return ($this->removeHtml) ? strip_tags($value) : $value;
    }

    /**
     * @param $column DataColumn
     * @return bool
     */
    protected function isColumnExportable($column)
    {
        if ($column instanceof ActionColumn || $column instanceof CheckboxColumn) {
            return false;
        }
        if (!empty($this->exportColumns)) {
            return in_array($column->attribute, $this->exportColumns);
        }
        return true;
    }


}