<?php

namespace kak\widgets\grid\services\manager;

use Box\Spout\Common\Manager\OptionsManagerAbstract;

/**
 * Options manager for JSON writer.
 */
class JsonOptionsManager extends OptionsManagerAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions(): void
    {
    }
}
