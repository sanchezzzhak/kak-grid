<?php


class GridViewTest extends \Codeception\Test\Unit
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

        $grid = new  \kak\widgets\grid\GridView([
            'id' => 'grid',
            'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => [ $model ]]),
            'view' => new \yii\web\View(),
        ]);

        echo $grid->run();


    }

}
