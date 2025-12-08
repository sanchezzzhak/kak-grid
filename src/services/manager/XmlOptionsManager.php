<?php

namespace kak\widgets\grid\services\manager;

use Box\Spout\Common\Manager\OptionsManagerAbstract;

/**
 * Options manager for XML writer.
 */
class XmlOptionsManager  extends OptionsManagerAbstract
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
    protected function setDefaultOptions()
    {
    }
}
