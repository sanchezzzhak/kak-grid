<?php
namespace kak\widgets\grid\behaviors;
use yii\base\Behavior;
use yii\helpers\Html;

/**
 * Class PageSizeBehavior
 * @package kak\widgets\grid\behaviors
 */
class PageSizeBehavior extends Behavior
{
    /** @var $owner kak\widgets\grid\GridView  */

    /*
     * @var integer the defualt page size. This page size will be used when the $_GET['per-page'] is empty.
     */
    public $defaultPageSize = 100;

    /**
     * @var string the name of the GET request parameter used to specify the size of the page.
     * This will be used as the input name of the dropdown list with page size options.
     */
    public $pageSizeParam = 'per-page';

    /**
     * @var array the list of page sizes
     */
    public $sizes = [
        20 => 20, 25 => 25, 50 => 50, 100 => 100, 200 => 200
    ];

    /**
     * @var string the template to be used for rendering the output.
     */
    public $template = '{list}';

    /**
     * @var array the list of options for the drop down list.
     */
    public $options;


    /**
    ```php
    'behaviors' => [
        [
            'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
            'toolbar' => [
                [
                    'content' => '{pagesize}' // attach behaver
                ]
            ]
        ],[
            'class' => \kak\widgets\grid\behaviors\PageSizeBehavior::className(),
        ]
    ],
    ```
     * render the output
     */
    public function renderPageSize()
    {
        $this->options['data-role'] = 'page-size';
        $perPage = !empty($_GET[$this->pageSizeParam]) ? $_GET[$this->pageSizeParam] : $this->defaultPageSize;
        if(!isset($this->options['class'])){
            Html::addCssClass($this->options, 'form-control');
        }

        $listHtml = Html::dropDownList($this->pageSizeParam, $perPage, $this->sizes, $this->options);
        $output = str_replace(['{list}'], [$listHtml], $this->template);

        return $output;
    }


}