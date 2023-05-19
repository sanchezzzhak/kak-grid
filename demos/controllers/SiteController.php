<?php
namespace app\controllers;

use yii\bootstrap\BootstrapAsset;
use yii\data\ArrayDataProvider;

class SiteController extends \yii\web\Controller
{
    public function init()
    {
        BootstrapAsset::register($this->getView());
        parent::init();
    }

    public function actionIndex()
    {
        $data = [
            [
                "name" => "Paul Cunningham",
                "text" => "eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula.",
                "currency" => "$41.07",
                "phone" => "(921) 904-5514",
                "date" => "27.01.23"
            ],
            [
                "name" => "Kaitlin Collier",
                "text" => "in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu",
                "currency" => "$92.40",
                "phone" => "(987) 719-0601",
                "date" => "02.10.22"
            ],
            [
                "name" => "Bree Rivas",
                "text" => "eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum.",
                "currency" => "$14.02",
                "phone" => "(147) 351-3110",
                "date" => "28.09.22"
            ],
            [
                "name" => "Bert Johnson",
                "text" => "vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor",
                "currency" => "$36.58",
                "phone" => "(821) 460-2727",
                "date" => "27.01.23"
            ],
            [
                "name" => "Carol Johnson",
                "text" => "purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis",
                "currency" => "$94.85",
                "phone" => "1-533-532-5295",
                "date" => "16.10.23"
            ]
        ];

        $provider = new ArrayDataProvider([
            'models' => $data
        ]);


        return $this->render('/site/index', compact('provider'));
    }

}