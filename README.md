GridView widgets for Yii2
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kak/grid "*"
```

Usage
-
Once the extension is installed, simply use it in your code by:

```php
<?php

use kak\widgets\grid\GridView; 
use yii\grid\ActionColumn;
use yii\helpers\Html;
  
echo GridView::widget([
    'showFooter' => true,
    'dataProvider' => $provider,
    'columns' => [
      'user' => [
          'header' => 'user',
          'format' => 'html',
          'value'  => static function(Stat $data){
              $htmlLink = Html::a($data->user->name,['user/update', 'id' =>  $data->user->id]);
              return sprintf('[%s] %s', $data->user_id, $htmlLink);
          }
      ],
      'date_key',
      'os',
      'browser',
      'operator_id' => [
          'header' => 'Operator',
          'value' => 'operator.name'
      ],
      'country_id' => [
          'header' => 'Country',
          'format' => 'html',
          'value' => static function(Stat $data){
              return Html::img($data->country->flag_url, ['title' => $data->country->name_ru]);
          },
         'footer' =>  '<b>Total redirect</b>',
      ],
      'view_count' => [
          'attribute' => 'view_count',
          'summary' => 'sum'
      ],
      'redirect_count' => [
          'attribute' => 'redirect_count',
          'summary' => 'sum'
      ],
      'ratio (redirect/view)' => [
          'header' => 'Ratio',
          'value' => static function($data){
              return round( (int)$data->redirect_count / (int)$data->view_count, 2);
          }
      ],
      'actions' => [
          'class' => ActionColumn::class,
          'template' => '{view}',
      ],
    ]
])?>
```

#### DataColumn
```php

use kak\widgets\grid\GridView;
use kak\widgets\grid\helpers\GridHelper;

echo GridView::widget([
     // ...
     'columns' => [
         'hits_sum' => [
             'format' => ['decimal', 2],
             'attribute' => 'hits_sum',
             'summary' => 'sum'
         ]
 ]
 ]);

 // set custom summary function
echo GridView::widget([
 // ...
    'columns' => [
         'hits_sum' => [
             'attribute' => 'hits_sum',
             'summary' => static function($models, $column){
                   return GridHelper::summary(
                        $models[$column] ?? [],
                        GridHelper::SUMMARY_SUM
                    );
             }
        ]
    ]
]);
```

### LabelColumn
```php
use kak\widgets\grid\columns\LabelColumn;
use kak\widgets\grid\GridView;
 // set custom summary function
 echo GridView::widget([
     // ...
     'columns' => [
         'hits_sum' => [
             'class' => LabelColumn::class,
             'attribute' => 'hits_sum',
         ]
     ]
 ]);
```

### SwapColumn
```php
use kak\widgets\grid\columns\SwapColumn;
use kak\widgets\grid\GridView;

echo GridView::widget([
 // ...
    'columns' => [
        'hits_sum' => [
            'class' => SwapColumn::class,
            'attribute' => 'hits_sum',
        ]
    ]
]);
```

### SwapColumn
```php
use kak\widgets\grid\columns\MiniColumn;
use kak\widgets\grid\GridView;

echo GridView::widget([
    // ...
    'columns' => [
        'id' => [
            'class' => MiniColumn::class,
            'attribute' => 'id',
        ]
    ]
]);
```

## Behaviors

### ToolBarBehavior and PageSizeBehavior
```php
    use kak\widgets\grid\behaviors\PageSizeBehavior;
    use kak\widgets\grid\behaviors\ToolBarBehavior;
    use kak\widgets\grid\GridView;
    
    $behaviors = [
        [
            'class' => ToolBarBehavior::class,
            'toolbar' => [
                 [ 'content' => '{PageSize}']
            ]
        ], [
            'class' => PageSizeBehavior::class,
        ]
    ];

    echo GridView::widget([
        'behaviors' => $behaviors,
        'columns' => [
           // ...
        ]
    ]);

```
### ResizableColumnsBehavior
```php
use kak\widgets\grid\behaviors\ResizableColumnsBehavior;
use yii\web\JsExpression;
$behaviors = [
    [
        'class' => ResizableColumnsBehavior::class,
        'clientOptions' => [
            'selector' => new JsExpression('function selector($table) {... see js code ...}'),
            'store' => new JsExpression('window.store'),
            'syncHandlers' => true,
            'resizeFromBody' => true,
            'maxWidth' => new JsExpression('null'),
            'minWidth' => 20
        ]
    ]
];
```

### ExportTableBehavior
```php
use kak\widgets\grid\behaviors\ExportTableBehavior;
use kak\widgets\grid\behaviors\ToolBarBehavior;
$behaviors = [
     [
         'class' => ToolBarBehavior::class,
         'toolbar' => [
             [
                 // attach ExportTableBehavior placeholder
                 'content' => '<div class="form-group">'
                     . '<div class="col-md-2 col-sm-4">{pagesize}</div>'
                     . '<div class="col-md-2">{exporttable}</div>'
                     . '</div>'
             ]
         ]
     ],[
         'class' => ExportTableBehavior::class,
     ]
];
```
### MenuColumnsBehavior
use kak\widgets\grid\behaviors\MenuColumnsBehavior;
use kak\widgets\grid\behaviors\ToolBarBehavior;
```php
$behaviors = [
     [
         'class' => ToolBarBehavior::class,
         'toolbar' => [
             [ 'content' => '{MenuColumns}']
         ]
     ], [
        'class' => MenuColumnsBehavior::class,
     ]
];
```

### Objects Grid
Create class and extends for class kak\widgets\grid\AbstractGrid

```php
use kak\widgets\grid\AbstractGrid;

Class UserGrid extends AbstractGrid
{
    // optional
    public $group;
            
    public function columns(): array
    {
        $columns = [
           'id',
            'username',
            'example for method column' => $this->getExampleColumn(),
        ];
        // remove column for group for rules
        return $this->composeColumns($columns, $this->group);
    }
    
    private function getExampleColumn(): array
    {
        // $this
        return [
             

        ];
    }
}

```