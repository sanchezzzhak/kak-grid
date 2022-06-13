<?php

use Codeception\Test\Unit;
use kak\widgets\grid\columns\DataColumn;
use kak\widgets\grid\GridView;

class DataColumnTest extends Unit
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

        $grid = new  GridView([
            'id' => 'grid',
            'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => [$model]]),
            'view' => new \yii\web\View(),
        ]);

        // test array contentOptions
        $columnArrayDD = new DataColumn([
            'grid' => $grid,
            'contentOptions' => [
                'data-test-content-option' => 'Array'
            ],
        ]);

        $this->assertStringContainsString('data-test-content-option="Array"',
            $columnArrayDD->renderDataCell($model, 'id', 0)
        );

        // test closure contentOptions
        $columnClosureDD = new DataColumn([
            'grid' => $grid,
            'contentOptions' => function ($data, $key, $index, $column) {
                return [
                    'data-test-content-option' => 'Closure'
                ];
            }
        ]);

        $this->assertStringContainsString('data-test-content-option="Closure"',
            $columnClosureDD->renderDataCell($model, 'id', 0)
        );

        // test closure array
        $columnClosureDD = new DataColumn([
            'grid' => $grid,
            'contentOptions' => [$this, '_getContentOptionsCellRenderClosureArray']
            /** @see _getContentOptionsCellRenderClosureArray */
        ]);

        $this->assertStringContainsString('data-test-content-option="ClosureArray"',
            $columnClosureDD->renderDataCell($model, 'id', 0)
        );


    }

}