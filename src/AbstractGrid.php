<?php

namespace kak\widgets\grid;

use yii\base\BaseObject;
use yii\base\Model;
use yii\data\BaseDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

    /**
     * Ready config for GridView
     *
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
     * ```
     *  return [
     *      'columnName1' => $this->getStatus1Column()
     *      'columnName2' => $this->getStatus2Column()
     *  ];
     * ```
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
     * @return Model
     */
    public function getFilterModel()
    {
        return $this->filterModel;
    }

    /**
     * @param Model $filterModel
     */
    public function setFilterModel($filterModel): void
    {
        $this->filterModel = $filterModel;
    }

    /**
     * @param float $value
     * @param int $decimals
     * @return string
     */
    protected function asFormatNumber(float $value, int $decimals = 2): string
    {
        return \Yii::$app->getFormatter()->asDecimal($value, $decimals);
    }

    /**
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
     * @param $value
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function asFormatMoney($value): string
    {
        return \Yii::$app->getFormatter()->asCurrency($value);
    }

    /**
     * @param $value
     * @param null $deciaml
     * @return string
     */
    protected function asDecimal($value, $deciaml = null): string
    {
        return \Yii::$app->getFormatter()->asDecimal($value, $deciaml);
    }
}
