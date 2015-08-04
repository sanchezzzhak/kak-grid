<?php
namespace kak\widgets\grid;
use Yii;
use yii\base\View;
use yii\data\ActiveDataProvider;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class GridView
 * @package kak\widgets\grid
 */
class GridView extends \yii\grid\GridView
{
    const SUMMARY_SUM  = 'sum';
    const SUMMARY_COUNT = 'count';
    const SUMMARY_AVG = 'avg';
    const SUMMARY_MAX = 'max';
    const SUMMARY_MIN = 'min';

    public $paginationPageSize = [20,50,100];

    public function init()
    {
        $this->prepareInitSort();
        GridViewAsset::register($this->getView());
        parent::init();
    }

    public function run()
    {
        $this->prepareShowHideColumns();

        echo Html::beginTag('div',['class' => 'kak-grid']);
            $this->renderShowHideColumns();

            parent::run();
        echo Html::endTag('div');

    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            if(!$column->visible) continue;
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }


    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            if(!$column->visible) continue;
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof \Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }



    protected function prepareShowHideColumns()
    {
        $key = 'kak-grid_'.$this->id;
        if(isset($_COOKIE[$key])){
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
    }

    protected function prepareInitSort()
    {
        $key = 'kak-grid_'.$this->id;
        if(isset($_COOKIE[$key])){
            $jsonConfig = Json::decode($_COOKIE[$key]);
            if($this->dataProvider->pagination){
                /** @var ActiveDataProvider $data */
                $this->dataProvider->pagination->setPageSize(  ArrayHelper::getValue($jsonConfig,'paginationSize',20));
            }
        }
    }


    protected function renderShowHideColumns()
    {
        $items = [];
        /** @var $column DataColumn */
        foreach($this->columns as $column){
            $items[] = ['label' => Html::checkbox('',$column->visible,[]) . '&nbsp;' . strip_tags($column->renderHeaderCell()), 'url' => '#',  'encode' => false ];
        }

        echo Html::beginTag('div',['class' => 'clearfix kak-grid-panel']);

            if($this->dataProvider->pagination) {
                echo Html::beginTag('div',['class' => 'btn-group pull-left']);
                echo Html::dropDownList('',
                    $this->dataProvider->pagination->getPageSize(),
                    array_combine(array_values($this->paginationPageSize),$this->paginationPageSize),
                    ['class' => 'pagination-size form-control' ]
                );
                echo Html::endTag('div');
            }


            echo Html::beginTag('div',['class' => 'dropdown-checkbox btn-group pull-right']);
                echo Html::tag('button','Show / hide columns',['class' => ' btn btn-default dropdown-toggle' , 'data-toggle' => 'dropdown']);
                echo \yii\bootstrap\Dropdown::widget([
                        'items' => $items,
                        'options' => [ 'class' => 'dropdown-checkbox-content' ]
                ]);
            echo Html::endTag('div');

        echo Html::endTag('div');
    }


    /**
     * Renders the table footer.
     * @return string the rendering result.
     */
    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            if(!$column->visible) continue;
            /* @var $column DataColumn */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_FOOTER) {
            $content .= $this->renderFilters();
        };
        return "<tfoot>\n" .  $content . "\n</tfoot>";
    }

    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters()
    {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column Column */
                if(!$column->visible) continue;
                $cells[] = $column->renderFilterCell();
            }
            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }


    /**
     * Renders the data models for the grid view.
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

        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }

    /**
     * @param $data
     * @param $type
     * @return number
     */
    public static function footerSummary($data,$attribute,$type)
    {
        $data = ArrayHelper::getColumn($data,$attribute);
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