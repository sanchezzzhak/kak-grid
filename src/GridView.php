<?php

namespace kak\widgets\grid;

use kak\widgets\grid\helpers\GridHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kak\widgets\grid\bundles\GridViewAsset;

/**
 * Class GridView
 * @package kak\widgets\grid
 * @property $behaviors;
 * @property $sortArrow;
 */
class GridView extends \yii\grid\GridView
{
    public const SORT_ARROW_DIRECTION = 'sort-direction';
    public const SORT_ARROW_ORDINAL = 'sort-ordinal';
    public const SORT_ARROW_NUMERICAL = 'sort-numerical';

    // deprecated block remove next version --->
    public const SUMMARY_SUM = 'sum';
    public const SUMMARY_COUNT = 'count';
    public const SUMMARY_AVG = 'avg';
    public const SUMMARY_MAX = 'max';
    public const SUMMARY_MIN = 'min';

    /*** @var string */
    public $dataColumnClass;
    /*** @var string */
    public $layout = "{{summary}\n{items}\n{pager}";
    /*** @var bool show direct sorted column up/down */
    public $sortArrow = false;
    /*** @var array */
    public $contentOptions = [];
    /** @var array  */
    private $_behaviors = [];

    /**
     * Provide the option to be able to set behaviors on GridView configuration.
     *
     * @param array $behaviors
     */
    public function setBehaviors(array $behaviors = [])
    {
        $this->_behaviors = $behaviors;
    }

    /**
     * get behaviors
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge($this->_behaviors, parent::behaviors());
    }

    public function init()
    {
        $this->dataColumnClass = columns\DataColumn::class;
        GridViewAsset::register($this->getView());
        Html::addCssClass($this->options, 'kak-grid');
        $this->initContainerOptions();

        parent::init();
    }

    public function initContainerOptions()
    {
        if (!$this->sortArrow) {
            return;
        }

        Html::addCssClass($this->contentOptions, 'icon-sort');

        if (is_string($this->sortArrow)) {
            Html::addCssClass($this->contentOptions, $this->sortArrow);
        }
    }

    public function run()
    {
        echo Html::beginTag('div', $this->contentOptions);
        $behaviors = $this->getBehaviors();
        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                if ($behavior->hasMethod('run')) {
                    $behavior->run();
                }
            }
        }
        parent::run();
        echo Html::endTag('div');
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        $method = sprintf('render%s', ucfirst(str_replace(['{', '}'], '', $name)));
        $behaviors = $this->getBehaviors();
        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                /** @var Object $behavior */
                if ($behavior->hasMethod($method)) {
                    return $behavior->$method();
                }
            }
        }
        return parent::renderSection($name);
    }

    /**
     * @param $data
     * @param $attribute
     * @param $type
     * @return number|string
     */
    public static function footerSummary($data, $attribute, $type)
    {
        return GridHelper::summary($data, $attribute, $type);
    }

} 