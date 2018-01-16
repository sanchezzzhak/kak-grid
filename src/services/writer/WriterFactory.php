<?php

namespace kak\widgets\grid\services\writer;
use Box\Spout\Common\Helper\GlobalFunctionsHelper;

class WriterFactory extends \Box\Spout\Writer\WriterFactory
{

    public static function create($writerType)
    {
        $writer = null;
        switch($writerType){
            case 'json': $writer = new WriterJson; break;
            case 'text': $writer = new WriterText; break;
//            case 'xml': break;
//            case 'pdf': break;
//            case 'txt': break;
            default: return parent::create($writerType);
        }
        $writer->setGlobalFunctionsHelper(new GlobalFunctionsHelper());
        return $writer;
    }
}