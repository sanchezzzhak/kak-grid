<?php

namespace kak\widgets\grid\services\writer;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\WriterAbstract;
use RuntimeException;

class WriterJson extends WriterAbstract implements KeyValueDataWriterInterface
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
    protected function addRowToWriter(Row $dataRow)
    {
        throw new RuntimeException('Method not implemented');
    }

    /**
     * @inheritdoc
     */
    public function addRowDataToWriter($dataRow)
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