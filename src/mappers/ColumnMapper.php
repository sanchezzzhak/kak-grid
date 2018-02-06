<?php
namespace kak\widgets\grid\mappers;

use kak\widgets\grid\columns\CheckboxColumn;
use kak\widgets\grid\columns\DataColumn;
use yii\db\ActiveRecordInterface;
use yii\grid\ActionColumn;

/**
 * Class ColumnMapper
 * @package kak\widgets\grid\mappers
 */
class ColumnMapper
{

    private $columns = [];
    private $exportColumns = [];
    private $removeHtml = true;
    private $columnHeader = true;

    public function __construct($columns, $exportColumns, $removeHtml, $columnHeader)
    {
        $this->columns = $columns;
        $this->exportColumns = $exportColumns;
        $this->removeHtml = $removeHtml;
        $this->columnHeader = $columnHeader;
    }


    public function getHeaders()
    {
        $headers = [];
        /** @var DataColumn $column */
        foreach ($this->columns as $column) {
            if ($this->isColumnExportable($column)) {
                $headers[] = $header = $this->columnHeader ? $this->getColumnHeader($column): $column->attribute;
            }
        }
        return $headers;
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
                    : isset($model[$column->attribute]) ? $model[$column->attribute]: null;

                $value = $this->getColumnValue($column, $model, $key, $index);
                $header = $this->columnHeader ? $this->getColumnHeader($column): $column->attribute;
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
        return trim(($this->removeHtml) ? strip_tags($value) : $value);
    }

    /**
     * @param $column DataColumn
     * @return string
     */
    protected function getColumnHeader($column)
    {
        $value = $column->renderHeaderCell();
        return trim(($this->removeHtml) ? strip_tags($value) : $value);
    }

    /**
     * @param $column DataColumn
     * @return bool
     */
    protected function isColumnExportable($column)
    {
        if ($column instanceof ActionColumn || $column instanceof CheckboxColumn || ($column instanceof DataColumn && $column->export === false)) {
            return false;
        }
        if (!empty($this->exportColumns)) {
            return in_array($column->attribute, $this->exportColumns);
        }
        return true;
    }


}