<?php

namespace kak\widgets\grid\services\writer;
use Box\Spout\Writer\CSV\Writer;

class WriterText extends Writer
{
    /**
     * @var string Content-Type value for the header
     */
    protected static $headerContentType = 'text/plain; charset=UTF-8';
    /**
     * @var string Defines the character used to delimit fields (one character only)
     */
    protected $fieldDelimiter = "\t";
}