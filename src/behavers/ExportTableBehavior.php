<?php
namespace kak\widgets\grid\behaviors;
use kak\widgets\grid\bundles\ExportTableAsset;
use kak\widgets\grid\interfaces\ExportType;
use yii\base\Behavior;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;

class ExportTableBehavior extends Behavior
{


    public $url = ['export'];

    public $dropDownOptions = [];

    public $renderType = '';


    /**
    ```php
    'behaviors' => [
        [
            'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
            'toolbar' => [
                [
                    'content' => '{exporttable}' // attach behavior
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
        //ExportType::GOOGLE => 'Google Spreadsheet',
        ExportType::ODS => 'Open Document Spreadsheet <span class="label label-default">.ods</span>',
        ExportType::JSON => 'JSON <span class="label label-default">.json</span>',
        ExportType::XML => 'XML <span class="label label-default">.xml</span>',
        ExportType::TXT => 'Text <span class="label label-default">.txt</span>',
        ExportType::HTML => 'HTML  <span class="label label-default">.html</span>'
    ];


    protected function processClearContent()
    {
        $this->processClearContent();
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    protected function processExport()
    {
        if (\Yii::$app->request->post('export') == 1) {

            exit;
        }
    }

    /**
     * Initializes the dropdown items
     */
    public function initButtonDropdown()
    {
        $this->processExport();

        $dropdown = [
            'encodeLabels' => false
        ];
        $hash = hash('crc32', $this->owner->getId() . 'export-table');
        $dropdown['items'][] = '';
        foreach ($this->types as $type => $label) {
            $dropdown['items'][] = [
                'label' => $label,
                'url' => Url::to($this->url) . '?' . \Yii::$app->request->getQueryString(),
                'options' => [
                    'data-role' => 'grid-export',
                    'data-hash' => $hash,
                ],
                'linkOptions' => [
                    'data-type' => $type,
                    'class' => 'export-link-format',
                ]
            ];
        }

        $html = ButtonDropdown::widget([
            'encodeLabel' => false,
            'label' => '<i class="glyphicon glyphicon-export"></i> Export',
            'dropdown' => $dropdown,
            'options' => $this->dropDownOptions
        ]);


        return $html;
    }



}