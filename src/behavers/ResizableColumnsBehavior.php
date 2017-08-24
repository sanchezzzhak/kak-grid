<?php

namespace kak\widgets\grid\behaviors;
use yii\base\Behavior;
use yii\helpers\Json;

class ResizableColumnsBehavior extends Behavior
{
    /**
     * @var array
     * ```
     * [
     *  'selector' => new JsExpression('function selector($table) {... see js code ...}'),
     *  'store' => new JsExpression('window.store'),
     *  'syncHandlers' => true,
     *  'resizeFromBody' => true,
     *  'maxWidth' => new JsExpression('null'),
     *  'minWidth' => 20
     * ]
     */
    public $clientOptions = [];

    public function run()
    {
        $id = $this->owner->getId();
        $view = $this->owner->getView();

        \kak\widgets\grid\bundles\ResizableColumnsAsset::register($view);

        $options = !empty($this->clientOptions)
            ? Json::htmlEncode($this->clientOptions)
            : '';

        $view->registerJs(";jQuery('#$id > table.table').resizableColumns($options);");

    }

}