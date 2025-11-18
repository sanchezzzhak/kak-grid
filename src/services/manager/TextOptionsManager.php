<?php

namespace kak\widgets\grid\services\manager;

use Box\Spout\Writer\CSV\Manager\OptionsManager;
use Box\Spout\Writer\Common\Entity\Options;

/**
 * Options manager for text writer.
 */
class TextOptionsManager extends OptionsManager
{
    /**
     * {@inheritdoc}
     */
    protected function setDefaultOptions()
    {
        parent::setDefaultOptions();

        $this->setOption(Options::FIELD_DELIMITER, "\t");
    }
}
