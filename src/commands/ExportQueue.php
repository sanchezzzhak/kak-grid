<?php
/**
 * Created by PhpStorm.
 * User: PHPdev
 * Date: 16.11.2017
 * Time: 19:58
 */

/**
 * Class ExportQueue
 * background job export table
 * procession next version;
 */
class ExportQueue extends \yii\base\Component implements \yii\queue\JobInterface
{
    public $grid;

    public function execute($queue)
    {

    }
}