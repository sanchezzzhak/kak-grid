<?php
namespace kak\widgets\grid\behaviors;
use kak\widgets\grid\bundles\ExportTableAsset;
use kak\widgets\grid\interfaces\ExportType;
use yii\base\Behavior;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class ExportTableBehavior extends Behavior
{
    public $url = ['export'];

    public $dropDownOptions = [];

    /**
     * @var array is empty lisr then export all columns
     */
    public $exportColumns = [];

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
        ExportType::CSV => 'CSV',
        ExportType::XLSX => 'Excel 2007+',
        //ExportType::GOOGLE => 'Google Spreadsheet',
        ExportType::ODS => 'Open Document Spreadsheet <span class="label label-default">.ods</span>',
        ExportType::JSON => 'JSON',
        ExportType::XML => 'XML',
        ExportType::TXT => 'TEXT',
        //ExportType::HTML => 'HTML',
        //ExportType::PDF => 'PDF'
    ];


    /**
     * run process export grid with dataProvider elements
     */
    protected function process()
    {
        if (Yii::$app->request->post('export') == 1){
            Yii::$app->response->clearOutputBuffers();
            $type = Yii::$app->request->post('type', null);

            $service = new \kak\widgets\grid\services\ExportService;
            $service->grid = $this->owner;
            $service->type = $type;
            $service->exportColumns = $this->exportColumns;
            $service->run();
        }
    }

    /**
     * Initializes the dropdown items
     */
    public function initButtonDropdown()
    {
        $this->process();

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