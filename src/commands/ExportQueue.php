<?php

use yii\queue\JobInterface;

/**
 * Class ExportQueue
 * background job export table
 * procession next version;
 */
class ExportQueue extends \yii\base\Component implements JobInterface
{
    public $grid;

    public function execute($queue)
    {

    }
}