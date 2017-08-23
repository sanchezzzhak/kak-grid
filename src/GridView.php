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
     * @param array $behaviors
     */
    public function behaviors()
    {
        return ArrayHelper::merge($this->_behaviors, parent::behaviors());
    }

    /**
     * @var null|string
     */
    public $tableWrapperClass = null;


    /**
     * @var string
     */
    public $dataColumnClass = '\kak\widgets\grid\columns\DataColumn';
    /**
     * @var string
     */
    public $layout = "
        {toolbar}
        {summary}
        {items}
        {pager}
    ";

    public function init()
    {
        GridViewAsset::register($this->getView());
        parent::init();
    }
    
    public function run()
    {
       // $this->prepareVisibilityColumns();
        echo Html::beginTag('div',['class' => 'kak-grid']);

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



    /**
     * Renders the data models for the grid view.
     * @return string
     */
    public function renderItems()
    {
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();

        $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;

        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableBody,
            $tableFooter
        ]);

        $contentTable = Html::tag('table', implode("\n", $content), $this->tableOptions);

        if (!is_null($this->tableWrapperClass)) {
            $contentTable = Html::tag('div', $contentTable, ['class' => $this->tableWrapperClass]);
        }

        return $contentTable;
    }

    const SUMMARY_SUM  = 'sum';
    const SUMMARY_COUNT = 'count';
    const SUMMARY_AVG = 'avg';
    const SUMMARY_MAX = 'max';
    const SUMMARY_MIN = 'min';
    /**
     * @param $data
     * @param $type
     * @return number|string
     */
    public static function footerSummary($data,$attribute,$type)
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


} 