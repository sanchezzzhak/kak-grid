<?php
namespace kak\widgets\grid;
use Yii;
use yii\base\View;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use kak\widgets\grid\bundles\GridViewAsset;

/**
 * Class GridView
 * @package kak\widgets\grid
 * @property $behaviors;
 */
class GridView extends \yii\grid\GridView
{
    const SORT_ARROW_DIRECTION = 'sort-direction';
    const SORT_ARROW_ORDINAL = 'sort-ordinal';
    const SORT_ARROW_NUMERICAL = 'sort-numerical';

    /**
     * @var bool show direct sorted column up/down
     */
    public $sortArrow = false;

    /**
     * @var array
     */
    public $contentOptions = [];

    private $_behaviors = [];
    /**
     * Provide the option to be able to set behaviors on GridView configuration.
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

    /**
     * @var string
     */
    public $dataColumnClass = '\kak\widgets\grid\columns\DataColumn';

    /**
     * @var string
     */
    public $layout = "
        {summary}
        {items}
        {pager}
    ";

    public function init()
    {
        GridViewAsset::register($this->getView());
        Html::addCssClass($this->options, 'kak-grid');
        $this->initContainerOptions();
        parent::init();
    }


    public function initContainerOptions()
    {
        if($this->sortArrow!=false){
            Html::addCssClass($this->contentOptions, 'icon-sort');
            if(is_string($this->sortArrow)){
                Html::addCssClass($this->contentOptions, $this->sortArrow );
            }
        }
    }


    public function run()
    {
        echo Html::beginTag('div', $this->contentOptions);
        $behaviors = $this->getBehaviors();
        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                if($behavior->hasMethod('run')) {
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
        $method = 'render' . ucfirst(str_replace(['{', '}'], '', $name));
        $behaviors = $this->getBehaviors();
        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                /** @var Object $behavior */
                if ($behavior->hasMethod($method)) {
                    return call_user_func([$behavior, $method]);
                }
            }
        }
        return parent::renderSection($name);
    }


    // deprecated block remove next version --->
    const SUMMARY_SUM  = 'sum';
    const SUMMARY_COUNT = 'count';
    const SUMMARY_AVG = 'avg';
    const SUMMARY_MAX = 'max';
    const SUMMARY_MIN = 'min';

    /**
     * @param $data
     * @param $attribute
     * @param $type
     * @return number|string
     * @deprecated remove next version and remove constant SUMMARY_*
     */
    public static function footerSummary($data, $attribute, $type)
    {
        $data = ArrayHelper::getColumn($data, $attribute);
        switch ($type) {
            case GridView::SUMMARY_SUM:
                return array_sum($data);
            case GridView::SUMMARY_COUNT:
                return count($data);
            case GridView::SUMMARY_AVG:
                return count($data) > 0 ? array_sum($data) / count($data) : null;
            case GridView::SUMMARY_MAX:
                return max($data);
            case GridView::SUMMARY_MIN:
                return min($data);
        }
        return '';
    }
    // deprecated block remove next version <---
} 