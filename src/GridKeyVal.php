<?php
namespace kak\widgets\grid;
use yii\grid\Column;
use yii\grid\DataColumn;
use \yii\widgets\BaseListView;
use yii\helpers\Html;

class GridKeyVal extends GridView
{
    public $group = null;
    public $groupOptions = [];


    /**
     * Renders the data models.
     * @return string the rendering result.
     */
    public function renderItems()
    {
        $caption = $this->renderCaption();

        $tableHeader = $this->renderTableHeader();

        $tableBody = $this->renderTableBody();
        //$tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
        $content = array_filter([
            $caption,
            $tableHeader,
           // $tableFooter,
            $tableBody,
        ]);
        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }

    public function renderTableHeader()
    {


    }

    protected function renderGroupRow($model, $key, $index)
    {
        /** @var Column $column */
        foreach ($this->columns as $col => &$column) {
            if ($col == $this->group) {
                $column->contentOptions['colspan'] = 2;
                return Html::tag('tr', $column->renderDataCell($model, $key, $index), $this->groupOptions );
            }
        }
        return null;
    }

    protected function renderGroupHeaderRow()
    {
        /** @var Column $column */
        foreach ($this->columns as $col => &$column) {
            if ($col == $this->group) {
                $column->headerOptions['colspan'] = 2;
                return Html::tag('tr', $column->renderHeaderCell(), $this->groupOptions );
            }
        }
        return null;
    }

    public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];

        $rows[]  = $this->renderGroupHeaderRow();

        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }


            $rows[]  = $this->renderGroupRow($model, $key, $index);

            /** @var Column $column */
            foreach ($this->columns as $col => $column) {
                $cells = [];
                if($col == $this->group) {
                    continue;
                }

                $cells[] = $column->renderHeaderCell();
                $cells[] = $column->renderDataCell($model, $key, $index);

                if ($this->rowOptions instanceof \Closure) {
                    $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
                } else {
                    $options = $this->rowOptions;
                }
                $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

                $rows[]  = Html::tag('tr', implode('', $cells), $options);
            }

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows)) {
            $colspan = count($this->columns);
            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
        }
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
    }


    /**
     * Renders the caption element.
     * @return bool|string the rendered caption element or `false` if no caption element should be rendered.
     */
    public function renderCaption()
    {
        if (!empty($this->caption)) {
            return Html::tag('caption', $this->caption, $this->captionOptions);
        } else {
            return false;
        }
    }

    public function init()
    {
        parent::init();


    }





}