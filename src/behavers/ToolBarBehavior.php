<?php
namespace kak\widgets\grid\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ButtonGroup;

/**
 * Class ToolBarBehavior
 * @package kak\widgets\grid\behaviors
 * ```php
    'behaviors' => [
        [
            'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
                'toolbar' => [
                    [
                        'content' => '{PageSize}'
                    ]
            ]
        ],[
            'class' => \kak\widgets\grid\behaviors\PageSizeBehavior::className(),
        ]
    ],
 * ```
 */
class ToolBarBehavior extends Behavior
{
    /** @var $owner kak\widgets\grid\GridView  */

    public $toolbar = [];


    /**
     * Renders the toolbar.
     * @return string
     */
    public function renderToolBar()
    {
        $content = Html::beginTag('div', ['class' => 'clearfix kak-grid-panel']);
            $toolbar = '';
            if (is_array($this->toolbar)) {
                foreach ($this->toolbar as $item) {
                    if (is_array($item)) {
                        $context = ArrayHelper::getValue($item, 'content', '');
                        $options = ArrayHelper::getValue($item, 'options', []);
                        Html::addCssClass($options, 'row-inline');
                        $toolbar .= Html::tag('div', $context, $options);
                    }
                }
            }

            $content.= preg_replace_callback("/{\\w+}/", function ($matches)  {
                $content = $this->owner->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $toolbar );

        $content.= Html::endTag('div');

        return $content;
    }



}