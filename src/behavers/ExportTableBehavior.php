<?php
namespace kak\widgets\grid\behaviors;
use kak\widgets\grid\interfaces\ExportType;
use yii\base\Behavior;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;

class ExportTableBehavior extends Behavior
{


    /**
    ```php
    'behaviors' => [
        [
            'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
            'toolbar' => [
                [
                    'content' => '{exporttable}' // attach behaver
                ]
            ]
        ],[
            'class' => \kak\widgets\grid\behaviors\ExportTableBehavior::className(),
        ]
    ],
    ```
     * render the output
     */
    public function renderExportTable()
    {
        $output = $this->initButtonDropdown();
        return $output;
    }

    public $types = [
        ExportType::CSV => 'CSV <span class="label label-default">.csv</span>',
        ExportType::XLSX => 'Excel 2007+ <span class="label label-default">.xlsx</span>',
        ExportType::GOOGLE => 'Google Spreadsheet',
        ExportType::ODS => 'Open Document Spreadsheet <span class="label label-default">.ods</span>',
        ExportType::JSON => 'JSON <span class="label label-default">.json</span>',
        ExportType::XML => 'XML <span class="label label-default">.xml</span>',
        ExportType::TXT => 'Text <span class="label label-default">.txt</span>',
        ExportType::HTML => 'HTML  <span class="label label-default">.html</span>'
    ];


    /**
     * Initializes the dropdown items
     */
    public function initButtonDropdown()
    {
        $dropdown = [
            'encodeLabels' => false
        ];

        foreach ($this->types as $type => $label) {
            $dropdown['items'][] = [
                'label' => $label,
                'url' => '#',
                'options' => [
                    'data-role' => 'grid-export'
                ],
                'linkOptions' => [
                    'data-type' => $type,
                    'class' => 'btn-da-exportable',
                ]
            ];
        }
        return ButtonDropdown::widget([
            'encodeLabel' => false,
            'label' => '<i class="glyphicon glyphicon-export"></i> Export',
            'dropdown' => $dropdown,
        ]);
    }



}