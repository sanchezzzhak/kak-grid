<?php
namespace kak\widgets\grid\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ButtonGroup;

class ToolBarBehaviors extends Behavior
{
//    /***
//     *
//     * ```
//     * 'buttons' => [
//     *      ['label' => 'A'],
//     *      ['label' => 'B', 'visible' => false],
//     *      '-', // divider
//     *      ['label' => 'C'], // this will belong to a different group
//     * ]
//     * ```
//     * @see http://www.yiiframework.com/doc-2.0/yii-bootstrap-buttongroup.html#$buttons-detail
//     */

//    /**
//     * @var boolean whether to HTML-encode the button labels of the button groups.
//     */
//    public $encodeLabels = true;
//    /**
//     * @var array $buttonGroupOptions the options to pass to the button groups. Currently are global. For example:
//     *
//     * ```
//     * 'buttonGroupOptions' => ['class' => 'btn-group-lg']
//     * ```
//     */
//    public $buttonGroupOptions = [];
//    /**
//     * @var array the options for the toolbar tag.
//     */
//    public $options = [];
//    /**
//     * @var array the options for the toolbar container.
//     */
//    public $containerOptions = [];
//    /**
//     * @var string toolbar alignment, defaults to right alignment.
//     */
//    public $alignRight = true;
//    /**
//     * @var array contains the grouped buttons
//     */
//    protected $groups = [];
//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        $this->initOptions();
//        $this->initButtonGroups();
//    }

    public $toolbar = [
        '{buttons}'
    ];


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

                        echo "1-1\n";

                        $content = ArrayHelper::getValue($item, 'content', '');
                        $options = ArrayHelper::getValue($item, 'options', []);
                        Html::addCssClass($options, 'btn-group');
                        $toolbar .= Html::tag('div', $content, $options);
                    } else {
                        $toolbar .= "\n{$item}";
                    }
                }
            }
            /*
            $content.= preg_replace_callback("/{\\w+}/", function ($matches)  {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $toolbar );*/
            //

        $content.= $toolbar;
        $content.= Html::endTag('div');

        return $content;
    }

    ///**
    // * @inheritdoc
    // */
    //protected function renderSection($name)
    //{
    //    return $name;
    //}




}