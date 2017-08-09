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

    /** @var array */
    public $paginationPageSize = [20,50,100,300];
    /** @var int  */
    public $defaulPageSize = 100;
    /**
     * @var null|string
     */
    public $tableWrapperClass = null;
    /**
     * @var bool
     */
    public $showMenuColumn  =  false;
    /** @var bool */
    public $showPageSize = true;
    /** @var string */

    /**
     * @var array
     */
    public $toolbar = [
        'default' => '
            <div class="btn-group pull-left">{pageSize}</div>
            <div class="btn-group pull-right">{menu}</div>
            <div class="btn-group pull-right">{actions}</div>
        '
    ];
    /**
     * @var string
     */
    public $dataColumnClass = '\kak\widgets\grid\DataColumn';
    /**
     * @var string
     */
    public $layout = "{toolbar}\n{summary}\n{items}\n{pager}";

    public function init()
    {
        GridViewAsset::register($this->getView());
        parent::init();
    }
    
    public function run()
    {
        $this->prepareVisibilityColumns();
        echo Html::beginTag('div',['class' => 'kak-grid']);
            parent::run();
        echo Html::endTag('div');
    }

    public function renderActions()
    {
        return '';
    }

    public function renderPageSize()
    {
        if (!$this->paginationPageSize || !count($this->paginationPageSize)) {
            return '';
        }
        $content = Html::dropDownList('', self::getPaginationSize(),
            array_combine(array_values($this->paginationPageSize), $this->paginationPageSize),
            ['class' => 'pagination-size form-control']
        );
        return $content;

    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch($name) {
            case '{menu}':
                return $this->renderMenu();
            case '{toolbar}':
                return $this->renderToolbar();
            case '{pageSize}':
                return $this->renderPageSize();
            case '{actions}':
                return $this->renderActions();
        }
        return parent::renderSection($name);
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



    protected function prepareVisibilityColumns()
    {
        $key = 'kak-grid_'.$this->id;
        if(count($this->columns) > 0 && $this->showMenuColumn && isset($_COOKIE[$key])){
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

    public static function getPaginationSize()
    {
        $key = 'kak-grid-page-size';
        return $value = Yii::$app->session->get($key, 100);
    }


    public function renderMenu()
    {
        if (!$this->showMenuColumn) {
            return '';
        }

        $items = [];
        /** @var $column DataColumn */
        foreach ($this->columns as $column) {

            if (ArrayHelper::getValue($column->options, 'menu', true))
                $items[] = [
                    'label' => Html::checkbox('', $column->visible, []) . '&nbsp;' . strip_tags($column->renderHeaderCell()),
                    'url' => '#', 'encode' => false
                ];
        }

        $content = Html::beginTag('div', ['class' => 'dropdown-checkbox btn-group']);
        $content.= Html::tag('button', $this->menuColumnsBtnLabel , ['class' => ' btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']);
        $content.= \yii\bootstrap\Dropdown::widget([
            'items' => $items,
            'options' => ['class' => 'dropdown-checkbox-content']
        ]);
        $content.= Html::endTag('div');

        return $content;
    }


    protected function renderToolbar()
    {
        $content = Html::beginTag('div', ['class' => 'clearfix kak-grid-panel']);

        $toolbar = '';
        if (is_string($this->toolbar)) {
            $toolbar = $this->toolbar;
        }

        if (is_array($this->toolbar)) {
            foreach ($this->toolbar as $item) {
                if (is_array($item)) {
                    $content = ArrayHelper::getValue($item, 'content', '');
                    $options = ArrayHelper::getValue($item, 'options', []);
                    Html::addCssClass($options, 'btn-group');
                    $toolbar .= Html::tag('div', $content, $options);
                } else {
                    $toolbar .= "\n{$item}";
                }
            }
        }

        $content.= preg_replace_callback("/{\\w+}/", function ($matches)  {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $toolbar);
        $content.= Html::endTag('div');

        return $content;
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
        }

        return '';
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

    /**
     * @param $data
     * @param $type
     * @return number|string
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