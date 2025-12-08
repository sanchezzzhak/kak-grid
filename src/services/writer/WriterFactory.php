<?php

namespace kak\widgets\grid\services\writer;

use Box\Spout\Common\Creator\HelperFactory;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Common\Helper\GlobalFunctionsHelper;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use kak\widgets\grid\interfaces\ExportType;
use kak\widgets\grid\services\manager\JsonOptionsManager;
use kak\widgets\grid\services\manager\TextOptionsManager;
use kak\widgets\grid\services\manager\XmlOptionsManager;

class WriterFactory extends WriterEntityFactory
{
    public static function create($writerType)
    {
        switch ($writerType) {
            case ExportType::CSV:
                return self::createCSVWriter();
            case ExportType::XLSX:
                return self::createXLSXWriter();
            case ExportType::ODS:
                return self::createODSWriter();
            case ExportType::JSON:
                return self::createJsonWriter();
            case ExportType::TXT:
                return self::createTextWriter();
            case ExportType::XML:
                return self::createXmlWriter();
        }

        throw new UnsupportedTypeException();
    }

    public static function createJsonWriter()
    {
        try {
            $optionsManager = new JsonOptionsManager();
            $functionsHelper = new GlobalFunctionsHelper();
            $helperFactory = new HelperFactory();
            return new WriterJson($optionsManager, $functionsHelper, $helperFactory);
        } catch (UnsupportedTypeException $ex) {
            // should never happen
        }
    }

    public static function createTextWriter()
    {
        try {
            $optionsManager = new TextOptionsManager();
            $functionsHelper = new GlobalFunctionsHelper();
            $helperFactory = new HelperFactory();
            return new WriterText($optionsManager, $functionsHelper, $helperFactory);
        } catch (UnsupportedTypeException $ex) {
            // should never happen
        }
    }

    public static function createXmlWriter()
    {
        try {
            $optionsManager = new XmlOptionsManager();
            $functionsHelper = new GlobalFunctionsHelper();
            $helperFactory = new HelperFactory();
            return new WriterXml($optionsManager, $functionsHelper, $helperFactory);
        } catch (UnsupportedTypeException $ex) {
            // should never happen
        }
    }
}