<?php

namespace kak\widgets\grid;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\i18n\Formatter;

/**
 * Class AbstractGrid
 * @package app\grid
 */
abstract class AbstractGrid extends BaseObject
{
    /** @var BaseDataProvider */
    private $provider;
    /** @var Model */
    private $filterModel;

    public $formatMoneyOptions = [];
    public $formatMoneyTextOptions = [];
    public $formatDecimalOptions = [];
    public $formatDecimalTextOptions = [];

    /**
     * Ready config for GridView
     * @example
     * the code for controller
     * ```php
     * $model = new SearchUserForm();
     * $grid = new GridStat([
     *    'provider' => $model->search($this->request->get()),
     * ]);
     * return $this->render('index', compact('grid', 'model'))
     * ```
     * the code for view
     * ```php
     * echo GridView::widget($grid->getConfig());
     * ```
     * or
     * ```
     * GridView::widget([
     *    'dataProvider' => $grid->getProvider(),
     *    'columns' => $grid->getColumns(),
     * ]);
     * ```
     * @return array
     * @throws \Exception
     */
    public function getConfig(): array
    {
        return [
            'tableOptions' => ['class' => 'table  table-striped'],
            'layout' => '{items} {pager}',
            'summary' => false,
            'options' => ['class' => 'table-responsive'],
            'dataProvider' => $this->getProvider(),
            'filterModel' => $this->getFilterModel(),
            'columns' => $this->columns(),
        ];
    }

    /**
     * Respect method name semantics for columns
     *
     * get<Name>Column
     * @return array
     * @example
     * ```php
     *  return [
     *      'columnName1' => $this->getStatus1Column()
     *      'columnName2' => $this->getStatus2Column()
     *  ];
     *```
     * or filter columns
     *
     * ```php
     *  return $this->composeColumns([
     *     'columnName1' => $this->getStatus1Column()
     *     'columnName2' => $this->getStatus2Column()
     *  ], $this->group);
     */
    abstract public function columns(): array;

    /***
     * List of fields that should be removed by some identifier for example group
     *
     * return [
     *      'columnName1' => [GROUP1],
     *      'columnName2' => [GROUP3],
     *  ];
     * @return array
     */
    public function rulesColumns(): array
    {
        return [];
    }

    /**
     * @param array $columns
     * @param string|null $group
     * @return array
     */
    public function composeColumns(array $columns, $group = null): array
    {
        foreach ($this->rulesColumns() as $columnKey => $rule) {
            if (is_array($rule) && !in_array($group, $rule, false)) {
                ArrayHelper::remove($columns, $columnKey);
            } else {
                $columns[$columnKey]['headerOptions']['data-attr'] = $columnKey;
            }
        }
        return $columns;
    }

    /**
     *  Get dataprovider class
     *
     * @return mixed
     * @throws \Exception
     */
    public function getProvider(): BaseDataProvider
    {
        if ($this->provider === null) {
            throw new \Exception('Provider not set properly.');
        }
        return $this->provider;
    }

    /**
     * @param BaseDataProvider $provider
     */
    public function setProvider(BaseDataProvider $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Get filter model
     *
     * @return Model
     */
    public function getFilterModel()
    {
        return $this->filterModel;
    }

    /**
     * Set filter model
     *
     * @param Model $filterModel
     */
    public function setFilterModel($filterModel): void
    {
        $this->filterModel = $filterModel;
    }

    /**
     * Get Formatter class
     *
     * @return Formatter
     */
    public function getFormatter(): Formatter
    {
        return \Yii::$app->getFormatter();
    }

    /**
     * Render format number
     *
     * @param float $value
     * @param int $decimals
     * @return string
     */
    protected function asFormatNumber(float $value, int $decimals = 2): string
    {
        return $this->getFormatter()->asDecimal($value, $decimals);
    }

    /**
     * Render html textarea
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    protected function renderHtmlTextArea(string $value = '', array $options = []): string
    {
        return Html::textarea('', $value, array_merge([
            'class' => 'form-control input-sm',
            'onclick' => 'this.focus();this.select()',
            'readonly' => true
        ], $options));
    }

    /**
     * Render html input text
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    protected function renderHtmlInputText(string $value = '', array $options = []): string
    {
        return Html::textInput('', $value, array_merge([
            'class' => 'form-control input-sm',
            'onclick' => 'this.focus();this.select()',
            'readonly' => true
        ], $options));
    }

    /**
     * Render format money
     *
     * @param int|float $value
     * @param string|null $currency
     * @param array|null $options
     * @param array|null $textOptions
     * @return string
     * @throws InvalidConfigException
     */
    protected function asFormatMoney(
        $value,
        $currency = null,
        ?array $options = null,
        ?array $textOptions = null
    ): string
    {
        return $this->getFormatter()->asCurrency(
            $value,
            $currency,
            $options ?? $this->formatMoneyOptions,
            $textOptions ?? $this->formatMoneyTextOptions
        );
    }

    /**
     * Render format decimal
     *
     * @param int|float $value
     * @param int|null $decimals
     * @param array|null $options
     * @param array|null $textOptions
     * @return string
     */
    protected function asDecimal(
        $value,
        $decimals = null,
        ?array $options = null,
        ?array $textOptions = null
    ): string
    {
        return $this->getFormatter()->asDecimal(
            $value,
            $decimals,
            $options ?? $this->formatDecimalOptions,
            $textOptions ?? $this->formatDecimalTextOptions
        );
    }
}
