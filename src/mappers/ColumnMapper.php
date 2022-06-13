<?php

namespace kak\widgets\grid\mappers;

use kak\widgets\grid\columns\DataColumn;
use yii\db\ActiveRecordInterface;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;

/**
 * Class ColumnMapper
 * @package kak\widgets\grid\mappers
 */
class ColumnMapper
{
    /** @var DataColumn[] */
    private $columns;

    private $exportColumns;
    private $removeHtml;
    private $columnHeader;

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
        foreach ($this->columns as $column) {
            if ($this->isColumnExportable($column)) {
                $headers[] = $this->columnHeader ? $this->getColumnHeader($column) : $column->attribute;
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

            if (!$this->isColumnExportable($column)) {
                continue;
            }

            $key = $model instanceof ActiveRecordInterface
                ? $model->getPrimaryKey()
                : $model[$column->attribute] ?? null;

            $value = $this->getColumnValue($column, $model, $key, $index);
            $header = $this->columnHeader ? $this->getColumnHeader($column) : $column->attribute;
            $row[$header] = $value;

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
        $hasExport = $column instanceof ActionColumn
            || $column instanceof CheckboxColumn
            || ($column instanceof DataColumn && $column->export === false);

        if ($hasExport) {
            return false;
        }

        if (!empty($this->exportColumns)) {
            return in_array($column->attribute, $this->exportColumns, false);
        }

        return true;
    }


}