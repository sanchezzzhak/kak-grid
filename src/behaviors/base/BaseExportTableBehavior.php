<?php

namespace kak\widgets\grid\behaviors\base;

use kak\widgets\grid\interfaces\ExportType;
use yii\base\Behavior;

/**
 * @package kak\widgets\grid\behaviors\base
 */
abstract class BaseExportTableBehavior extends Behavior
{
    /**
     * @var array format support export
     */
    public $types = [
        ExportType::CSV => 'CSV',
        ExportType::XLSX => 'Excel 2007+',
        //ExportType::GOOGLE => 'Google Spreadsheet',
        ExportType::ODS => 'Open Document Spreadsheet',
        ExportType::JSON => 'JSON',
        ExportType::XML => 'XML',
        ExportType::TXT => 'TEXT',
        //ExportType::HTML => 'HTML',
        //ExportType::PDF => 'PDF'
    ];
}
