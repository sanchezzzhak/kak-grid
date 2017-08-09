<?php
class DataColumnTest extends \Codeception\Test\Unit
{


        public function _getContentOptionsCellRenderClosureArray()
        {
            return [
                'data-test-content-option' => 'ClosureArray'
            ];
        }


       public function testContentOptionsCellRenderClosureArray()
       {
           $model = [
                'id' => 1,
                'reason' => 'test render closure options'
           ];

           $grid = new  \kak\widgets\grid\GridView([
               'id' => 'grid',
               'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => [ $model ]]),
               'view' => new \yii\web\View(),
           ]);

           // test array contentOptions
           $columnArrayDD = new \kak\widgets\grid\DataColumn([
               'grid' => $grid,
               'contentOptions' => [
                   'data-test-content-option' => 'Array'
               ],
           ]);
           $this->assertContains('data-test-content-option="Array"', $columnArrayDD->renderDataCell($model,'id',0));

           // test closure contentOptions
           $columnClosureDD = new \kak\widgets\grid\DataColumn([
               'grid' => $grid,
               'contentOptions' => function($data, $key, $index, $column) {
                   return [
                       'data-test-content-option' => 'Closure'
                   ];
               }
           ]);
           $this->assertContains('data-test-content-option="Closure"', $columnClosureDD->renderDataCell($model,'id',0));

           // test closure array
           $columnClosureDD = new \kak\widgets\grid\DataColumn([
               'grid' => $grid,
               'contentOptions' => [$this, '_getContentOptionsCellRenderClosureArray'] /** @see _getContentOptionsCellRenderClosureArray */
           ]);
           $this->assertContains('data-test-content-option="ClosureArray"', $columnClosureDD->renderDataCell($model,'id',0));


       }

}