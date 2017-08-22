<?php
namespace kak\widgets\grid\columns;


use kak\widgets\grid\GridView;
use yii\helpers\ArrayHelper;

class DataColumn extends \yii\grid\DataColumn
{

    /**
     * @var string|\Closure
     * @see \kak\widgets\grid\GridView::SUMMARY_SUM
     * @see \kak\widgets\grid\GridView::SUMMARY_COUNT
     * @see \kak\widgets\grid\GridView::SUMMARY_AVG
     * @see \kak\widgets\grid\GridView::SUMMARY_MAX
     * @see \kak\widgets\grid\GridView::SUMMARY_MIN
     * @example
     * ```php
        echo \kak\widgets\grid\GridView::widget([
            'columns' => [
                'hits_sum' => [
                    'format' => ['decimal', 2]
                    'attribute' => 'hits_sum',
                    'summary' => 'sum'
                ]
            ]
        ])
        echo \kak\widgets\grid\GridView::widget([
            'columns' => [
                'hits_sum' => [
                    'attribute' => 'hits_sum',
                    'summary' => function($models, $column){}
                ]
            ]
        ])
    function($models, $column){}
    ```
     */
    public $summary;

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
        if ($this->contentOptions instanceof Closure || is_callable($this->contentOptions)) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        return parent::renderDataCell($model, $key, $index);
    }


    /**
     * Renders the footer cell.
     */
    public function renderFooterCell()
    {
        if($footer = $this->renderFooterSummary()){
            $this->footer =  $this->grid->formatter->format($footer, $this->format);
        }
        return \yii\helpers\Html::tag('td', $this->renderFooterCellContent(), $this->footerOptions);
    }

    /**
     * Renders the footer cell summary
     * @return mixed|null|number|string
     */
    public function renderFooterSummary()
    {
        if ($this->summary instanceof Closure || is_callable($this->summary)) {
            return call_user_func($this->footerSummer, $this->grid->dataProvider->getModels(), $this);
        } else if (!empty($this->summary)) {
            return GridView::footerSummary($this->grid->dataProvider->getModels(), $this->attribute, $this->summary);
        }
        return null;
    }





}