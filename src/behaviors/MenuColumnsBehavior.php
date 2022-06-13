<?php

namespace kak\widgets\grid\behaviors;

use kak\widgets\grid\columns\DataColumn;
use yii\base\Behavior;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class MenuColumnsBehavior
 * @package kak\widgets\grid\behaviors
     * @property $owner \kak\widgets\grid\GridView
 *
 * ```php
    'behaviors' => [
        [
            'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
            'toolbar' => [
                [
                '   content' => '{MenuColumns}' // attach behavior MenuColumnsBehavior
                ]
            ]
        ],[
        'class' => \kak\widgets\grid\behaviors\MenuColumnsBehavior::className(),
        ]
    ],
 * ```
 */
class MenuColumnsBehavior extends Behavior
{
    public $label = 'show/hide columns';

    /**
     * render the output
     */
    public function renderMenuColumns()
    {
        $items = [];
        /** @var $column DataColumn */
        foreach ($this->owner->columns as $attr => $column) {
            if (ArrayHelper::getValue($column->options, 'menu', true)) {
                $items[] = [
                    'label' => Html::checkbox('', $column->visible, []) . '&nbsp;' . strip_tags($column->renderHeaderCell()),
                    'url' => '#',
                    'encode' => false,
                    'options' => ['data-name' => $attr],
                ];
            }
        }

        $content = Html::beginTag('div', ['class' => 'dropdown-checkbox btn-group', 'data-role' => 'grid-menu-columns']);
        $content .= Html::tag('button', $this->label, ['class' => ' btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']);
        $content .= Dropdown::widget([
            'items' => $items,
            'options' => ['class' => 'dropdown-checkbox-content']
        ]);
        $content .= Html::endTag('div');

        return $content;
    }

    public function run()
    {
        // load settings
        // hide default render
    }

    /*
    // refectory methods stash
    protected function prepareVisibilityColumns()
    {
        $key = 'kak-grid_'.$this->id;
        /*if(count($this->columns) > 0 && $this->showMenuColumn && isset($_COOKIE[$key])){
            $jsonConfig = Json::decode($_COOKIE[$key]);
            $columns = ArrayHelper::getValue($jsonConfig,'columns',[]);
            $i = 0;
            foreach($this->columns as &$column){
                if( isset($columns[$i]) ){
                    $column->visible = (bool)$columns[$i];
                }
                $i++;
            }
        }

    public static function getPaginationSize()
    {
        $key = 'kak-grid-page-size';
        return $value = Yii::$app->session->get($key, 100);
    }

*/


}