<?php
namespace kak\widgets\grid;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class DateColumn
 * @package kak\widgets\grid
 */
class DateRangeColumn extends \yii\grid\DataColumn
{

    public $labelDateFrom  = 'Date From';
    public $labelDateTo    = 'Date to';
    public $labelBtnApply  = 'Apply';

    public $btnOptions = [ 'class' => 'btn-default'];
    public $clientOptions = [
        'inline' => true,
        'locale' => 'ru'
    ];

     /**
     * @inheritdoc
     */
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;
        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {

            $dateParts =  explode('-', $model->{$this->attribute} );

            Html::addCssClass($this->btnOptions, 'btn btn-container-open');
            $content = Html::beginTag('div',['class'=> 'column-filter']);

                $content.= Html::beginTag('div',['class' => 'col-md-12 input-group' ]);
                    $content.= Html::beginTag('span',['class' => 'input-group-btn' ]);
                        $content.= Html::button('...',$this->btnOptions);
                    $content.= Html::endTag('span');
                    $content.= Html::activeTextInput($model,$this->attribute,['class' => 'form-control date-filter-range-input']);
                $content.= Html::endTag('div');


                $content.= Html::beginTag('div',['class'=> 'column-filter-container date-filter-range']);
                    $content.=Html::beginTag('div',['class' => 'row']);
                        $content.=Html::beginTag('div',['class' => 'dateFrom col-md-12']);
                            $content.=Html::button($this->labelBtnApply ,['class' => 'btn-apply btn btn-success']);
                        $content.= Html::endTag('div');
                    $content.= Html::endTag('div');

                    $content.=Html::beginTag('div',['class' => 'row']);
                        $content.=Html::beginTag('div',['class' => 'dateFrom col-md-6']);
                        $content.=Html::label($this->labelDateFrom);
                        $content.= \kak\widgets\datetimepicker\DateTimePicker::widget([
                            'name' => '',
                            'value'=> isset($dateParts[0]) ? trim($dateParts[0]) : '',
                            'clientOptions'  => $this->clientOptions,
                            'showInputIcon' => false,
                        ]);
                        $content.= Html::endTag('div');
                        $content.=Html::beginTag('div',['class' => 'dateTo col-md-6']);
                        $content.=Html::label($this->labelDateTo);
                        $content.= \kak\widgets\datetimepicker\DateTimePicker::widget([
                            'name' => '',
                            'value'=> isset($dateParts[1]) ? trim($dateParts[1]) : '',
                            'clientOptions'  => $this->clientOptions,
                            'showInputIcon' => false,
                        ]);
                        $content.= Html::endTag('div');
                        $content.= Html::endTag('div');

                $content.= Html::endTag('div');
            $content.= Html::endTag('div');


            $error = '';
            if ($model->hasErrors($this->attribute)) {
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            }

            /*

            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . $error;
            } else {

            }
            */
            return $content  . $error;
        }

        return  $this->grid->emptyCell; /*parent::renderFilterCellContent();*/
    }
}