GridView widgets for Yii2
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kak/grid "*"
```

Usage
-
Once the extension is installed, simply use it in your code by  :
```php
<?php

use kak\widgets\grid\GridView; 
use yii\helpers\Html;
  
echo GridView::widget([
    'showFooter' => true,
    'dataProvider' => $provider,
    'sortArrow' => GridView::SORT_ARROW_DIRECTION
    'columns' => [
      'user' => [
          'header' => 'user',
          'format' => 'html',
          'value'  => function($data){
              return '[' . $data->stream_id . '] '
              .  Html::a($data->user->name,['user/update', 'id' => $data->user->id ]);
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
          'value'  => function($data){
              return Html::img($data->country->flag_url,['title' => $data->country->name_ru]);
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
          'value' =>  function($data){
              return round( (int)$data->redirect_count/(int)$data->view_count ,2);
          }
      ],
      'actions' => [
          'class' => \yii\grid\ActionColumn::className(),
          'template' => '{view}',
      ],
    ]
])?>
```


Column types
-
* [DataColumn](docs/columns/data-column.md) ( support editable in processing)
* [LabelColumn](docs/columns/label-column.md)
* [SwapColumn](docs/columns/swap-column.md)  ( in processing )

Behaviors
-
* [ToolBarBehavior](docs/behaviors/toolbar-behavior.md) (base attach old beg panel plugins)
* [ExportTableBehavior](docs/behaviors/export-table-behavior.md) (export popular format json, csv, excel)
* [MenuColumnsBehavior](docs/behaviors/menu-column-behavior.md)  ( in processing )
* [PageSizeBehavior](docs/behaviors/page-size-behavior.md)
* AutoFilterBehavior ( in processing )
* [ResizableColumnsBehavior](docs/behaviors/resizable-columns-behavior.md)
    
    
<!--    
## test configuration
```
cd project_dir 
php composer.phar require kak/grid "dev-master"
download codecept.phar & save to dir roor
cd project_dir/vendor/kak/grid
php -f ../../../codecept.phar bootstrap
php -f ../../../codecept.phar build
```
-->