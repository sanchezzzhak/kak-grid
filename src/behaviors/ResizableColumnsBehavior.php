<?php

namespace kak\widgets\grid\behaviors;

use kak\widgets\grid\bundles\ResizableColumnsAsset;
use yii\base\Behavior;
use yii\helpers\Json;

/**
 * Class ResizableColumnsBehavior
 * @package kak\widgets\grid\behaviors

 */
class ResizableColumnsBehavior extends Behavior
{
    /**
     * @var array
     * ```php
     * [
     * 'selector' => new JsExpression('function selector($table) {... see js code ...}'),
     * 'store' => new JsExpression('window.store'),
     * 'syncHandlers' => true,
     * 'resizeFromBody' => true,
     * 'maxWidth' => new JsExpression('null'),
     * 'minWidth' => 20
     * ]
     * ```
     */
    public $clientOptions = [];

    public function run()
    {
        $id = $this->owner->getId();
        $view = $this->owner->getView();

        ResizableColumnsAsset::register($view);

        $options = !empty($this->clientOptions)
            ? Json::htmlEncode($this->clientOptions)
            : '';

        $view->registerJs(";jQuery('#$id > table.table').resizableColumns($options);");
    }

}