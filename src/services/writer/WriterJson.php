<?php

namespace kak\widgets\grid\services\writer;
use \Box\Spout\Writer\AbstractWriter;

class WriterJson extends AbstractWriter
{
    /**
     * @var int current position
     */
    protected $position = 0;
    /**
     * @var string Content-Type value for the header
     */
    protected static $headerContentType = 'application/json';
    /**
     * @inheritdoc
     */
    protected function openWriter()
    {
        fwrite($this->filePointer, '[');
    }
    /**
     * @inheritdoc
     */
    protected function addRowToWriter(array $dataRow, $style)
    {
        fwrite($this->filePointer, ($this->position > 0 ? ',' : '') . json_encode($dataRow));
        ++$this->position;
    }
    /**
     * @inheritdoc
     */
    protected function closeWriter()
    {
        fwrite($this->filePointer, ']');
    }
}