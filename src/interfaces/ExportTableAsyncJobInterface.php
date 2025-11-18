<?php

namespace kak\widgets\grid\interfaces;

use yii\queue\JobInterface;

/**
 * @package kak\widgets\grid\interfaces
 */
interface ExportTableAsyncJobInterface extends JobInterface
{
    /**
     * Sets the type.
     * @param string $type
     */
    public function setType($type);

    /**
     * Sets the salt.
     * @param string $salt
     */
    public function setSalt($salt);
}
