<?php

namespace kak\widgets\grid\columns;

use kak\widgets\grid\bundles\MinColumnAsset;
use yii\helpers\Html;

/**
 * Class MiniColumn
 * @package app\components\columns
 */
class MiniColumn extends DataColumn
{
    public $minWidth = 80;
    public $maxWidth = 280;
    public $defaultHide = true;

    public function init()
    {
        parent::init();
        MinColumnAsset::register($this->grid->view);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $content = parent::renderDataCellContent($model, $key, $index);
        return sprintf('<p class="grid-overtext-container">%s</p>', $content);
    }

    protected function renderHeaderCellContent()
    {
        $action = Html::button('<i class="fas fa-arrows-alt-h"></i>', [
            'class' => 'btn btn-xs btn-dark grid-btn-min-column',
            'data-min-width' => $this->minWidth,
            'data-max-width' => $this->maxWidth,
            'data-hide' => (int)$this->defaultHide,
        ]);
        $header = parent::renderHeaderCellContent();
        return sprintf('%s <span class="pull-right">%s</span>', $header, $action);
    }

}
