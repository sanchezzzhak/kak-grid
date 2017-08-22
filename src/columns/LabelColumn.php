<?php
namespace kak\widgets\grid\columns;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class LabelColumn
 * @package kak\widgets\grid\columns
 */
class LabelColumn extends DataColumn
{
    /**
     * @var array $labels the configuration on how to display the different label values.
     *
     * ```
      'active' => [                       // value cell
           'label' => 'subscribed',       // class label
           'options' => [
               'style' => 'padding: 2px'  // style label tag
           ]
      ],
     * ```
     *
     * The key names of the array will be used to match against the value. If a match is found, will render a bootstrap
     * label with the options provided.
     *
     * @see http://getbootstrap.com/components/#labels
     */
    public $labels = [];
    /**
     * @var string forcely set the format to HTML.
     */
    public $format = 'html';
    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (isset($this->labels[$value])) {
            $text = ArrayHelper::getValue($this->labels, "{$value}.label", $value);
            $options = ArrayHelper::getValue($this->labels, "{$value}.options", ['class' => 'label-default']);
            Html::addCssClass($options, 'label');
            return Html::tag('span', $text, $options);
        }
        return $value;
    }
}