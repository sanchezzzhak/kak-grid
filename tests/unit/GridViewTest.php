<?php


use Codeception\Test\Unit;
use kak\widgets\grid\GridView;
use yii\web\View;

class GridViewTest extends Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {

    }

    public function testMe()
    {

        $model = [
            'id' => 1,
            'reason' => 'test render closure options'
        ];

        $grid = new  GridView([
            'id' => 'grid',
            'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => [ $model ]]),
            'view' => new View(),
        ]);

        echo $grid->run();


    }

}
