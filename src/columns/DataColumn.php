<?php
namespace kak\widgets\grid\columns;


use kak\widgets\grid\GridView;
use kak\widgets\grid\helpers\GridHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class DataColumn
 * @package kak\widgets\grid\columns
 *
 * @see \kak\widgets\grid\helpers\GridHelper::SUMMARY_SUM
 * @see \kak\widgets\grid\helpers\GridHelper::SUMMARY_COUNT
 * @see \kak\widgets\grid\helpers\GridHelper::SUMMARY_AVG
 * @see \kak\widgets\grid\helpers\GridHelper::SUMMARY_MAX
 * @see \kak\widgets\grid\helpers\GridHelper::SUMMARY_MIN
 *
 * ```php
    // base
    echo \kak\widgets\grid\GridView::widget([
        'columns' => [
            'hits_sum' => [
                'format' => ['decimal', 2]
                'attribute' => 'hits_sum',
                'summary' => 'sum'
            ]
    ]
    ])
    // set custom summary function
    echo \kak\widgets\grid\GridView::widget([
        'columns' => [
            'hits_sum' => [
                'attribute' => 'hits_sum',
                'summary' => function($models, $column){}
            ]
        ]
    ])
 * ```
 */
class DataColumn extends \yii\grid\DataColumn
{

    /**
     * @var string|\Closure
     */
    public $summary;

    /**
     * @var bool
     */
    public $export = true;

    /** @var \kak\widgets\grid\GridView */
    public $grid;

    /**
     * render header
     *
     * @return string
     */
    public function renderHeaderCell()
    {
        $headerOptions = $this->headerOptions;
        if ($headerOptions instanceof \Closure || is_callable($headerOptions)) {
            $options = call_user_func($headerOptions, $this);
        }else{
            $options = $headerOptions;
        }
        return Html::tag('th', $this->renderHeaderCellContent(), $options);
    }

    /**
     * Renders a data cell.
     * @param mixed $model the data model being rendered
     * @param mixed $key the key associated with the data model
     * @param int $index the zero-based index of the data item among the item array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    public function renderDataCell($model, $key, $index)
    {
        // temp fix;
        if ($this->contentOptions instanceof \Closure || is_callable($this->contentOptions)) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }


    /**
     * Renders the footer cell content.
     * The default implementation simply renders [[footer]].
     * This method may be overridden to customize the rendering of the footer cell.
     * @return string the rendering result
     */
    public function renderFooterCellContent()
    {
        if($footer = $this->getFooterCellSummary()){
            $this->footer = $footer;
        }
        if(!empty($this->footer)){
            $this->footer =  $this->grid->formatter->format($this->footer, $this->format);
        }
        return parent::renderFooterCellContent();
    }

    /**
     * get cell summary column values
     * @return null|number
     */
    public function getFooterCellSummary()
    {
        if ($this->summary instanceof \Closure || is_callable($this->summary)) {
            return call_user_func($this->summary, $this->grid->dataProvider->getModels(), $this);
        }

        if (!empty($this->summary)) {
            return GridHelper::summary($this->grid->dataProvider->getModels(), $this->attribute, $this->summary);
        }
        return null;
    }





}