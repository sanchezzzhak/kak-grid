<?php
namespace kak\widgets\grid;


class DataColumn extends \yii\grid\DataColumn
{
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
}