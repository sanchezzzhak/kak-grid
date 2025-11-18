<?php

namespace kak\widgets\grid\services\writer;

/**
 * Interface for key value based data formats.
 */
interface KeyValueDataWriterInterface
{
    /**
     * Add an associative array to writer.
     * @param array<string, string|float|int|bool|null> $dataRow
     */
    public function addRowDataToWriter($dataRow);
}
