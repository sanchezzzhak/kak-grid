<?php
namespace kak\widgets\grid\interfaces;

/**
 * Support format export grid view
 * Interface ExportType
 * @package kak\widgets\grid\interfaces
 */
interface ExportType
{
    public const CSV = 'csv';
    public const XLSX = 'xlsx';
    public const GOOGLE = 'spreadsheets';
    public const ODS  = 'ods';
    public const JSON = 'json';
    public const JSON_ROW = 'json-row';
    public const XML  = 'xml';
    public const TXT  = 'txt';
    public const HTML = 'html';
    public const PDF = 'pdf';
}