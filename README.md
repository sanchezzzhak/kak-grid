# kak-grid
Grid, TreeGrid widgets for Yii2
Preview
-----------
<img src="https://lh3.googleusercontent.com/-ViaLrNwzD_8/Vb9fHnWvtPI/AAAAAAAAAEQ/sFC83sEAMhY/s480-Ic42/kakGridPreview.png">
Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kak/grid "dev-master"
```

or add

```
"kak/grid": "dev-master"
```

to the require section of your `composer.json` file.

Usage
-----
Once the extension is installed, simply use it in your code by  :
```php
     <?php use kak\widgets\grid\GridView; ?>
     <?=GridView::widget([
           'showPageSummary' => true,
          'dataProvider' => $provider,
          'columns' => [
              'stream_id' => [
                  'header' => 'Stream',
                  'format' => 'html',
                  'value'  => function($data){
                      return '[' . $data->stream_id . '] '. Html::a($data->stream->name,['/dashboard/stream/update', 'id' => $data->stream->id ]);
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
                 'options' => [
                    'summaryLabel' => '<b>Total redirect</b>',
                 ],
              ],
              'view_count' => [
                  'attribute' => 'view_count',
                  'options' => [
                      'summary' => GridView::SUMMARY_SUM,
                  ],
              ],
              'redirect_count' => [
                  'attribute' => 'redirect_count',
                  'options' => [
                       'summary' => GridView::SUMMARY_SUM,
                  ],
              ],
              'ratio (redirect/view)' => [
                  'header' => 'Ratio',
                  'value' =>  function($data){
                      return round( (int)$data->redirect_count/(int)$data->view_count ,2);
                  }
              ],
          ]
      ])?>
```