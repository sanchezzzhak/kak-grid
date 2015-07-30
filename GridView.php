<?php
namespace kak\widgets\grid;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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

    public $showPageSummary = true;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
    }

    /**
     * Renders the table footer.
     * @return string the rendering result.
     */
    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column DataColumn */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_FOOTER) {
            $content .= $this->renderFilters();
        }

        return $content;
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
        $pageSummary = $this->showPageSummary ? $this->renderPageSummary() : false;

        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            ( $tableFooter ||  $pageSummary ? "<tfoot>\n" .  $tableFooter . $pageSummary . "\n</tfoot>" : ""),
            $tableBody,
        ]);

        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }


    /**
     * @return string
     */
    public function renderPageSummary(){
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows  = $cells = [];

        foreach ($this->columns as $col => $column) {
            if(!$column->visible)
                continue;
            $summary = ArrayHelper::getValue($column->options,'summary',false);
            $summaryLabel = ArrayHelper::getValue($column->options,'summaryLabel',false);
            if($summary){
                foreach ($models as $index => $model) {
                    $key = $keys[$index];
                    $rows[$col][] = $column->getDataCellValue($model, $key, $index);
                }
                $cells[] = Html::tag('td', (isset($rows[$col]) ? $this->calculateSummary( $rows[$col] ,$summary) : ''),[]);
                continue;
            }
            $cells[] = Html::tag('td', ($summaryLabel) ? $summaryLabel : '',[]);
        }
        return Html::tag('tr', implode('', $cells), []);

    }

    /**
     * @param $data
     * @param $type
     * @return number
     */
    protected function calculateSummary($data,$type)
    {
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