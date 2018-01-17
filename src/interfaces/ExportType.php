<?php
namespace kak\widgets\grid\interfaces;

/**
 * Support format export grid view
 * Interface ExportType
 * @package kak\widgets\grid\interfaces
 */
interface ExportType
{
    const CSV = 'csv';
    const XLSX = 'xlsx';
    const GOOGLE = 'spreadsheets';
    const ODS  = 'ods';
    const JSON = 'json';
    const JSON_ROW = 'json-row';
    const XML  = 'xml';
    const TXT  = 'txt';
    const HTML = 'html';
    const PDF = 'pdf';
}