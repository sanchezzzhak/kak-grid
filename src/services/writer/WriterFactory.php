<?php

namespace kak\widgets\grid\services\writer;
use Box\Spout\Common\Helper\GlobalFunctionsHelper;
use kak\widgets\grid\interfaces\ExportType;

class WriterFactory extends \Box\Spout\Writer\WriterFactory
{

    public static function create($writerType)
    {
        $writer = null;
        switch ($writerType) {
            case ExportType::JSON:
                $writer = new WriterJson;
                break;
            case ExportType::TXT:
                $writer = new WriterText;
                break;
            case ExportType::XML:
                $writer = new WriterXml;
                break;
//            case 'pdf': break;
//            case 'txt': break;
            default:
                return parent::create($writerType);
        }
        $writer->setGlobalFunctionsHelper(new GlobalFunctionsHelper());
        return $writer;
    }
}