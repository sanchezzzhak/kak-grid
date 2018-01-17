<?php
/**
 * Created by PhpStorm.
 * User: PHPdev
 * Date: 17.01.2018
 * Time: 11:49
 */

namespace kak\widgets\grid\helpers;
use yii\helpers\ArrayHelper;

class GridHelper
{
    const SUMMARY_SUM  = 'sum';
    const SUMMARY_COUNT = 'count';
    const SUMMARY_AVG = 'avg';
    const SUMMARY_MAX = 'max';
    const SUMMARY_MIN = 'min';


    /**
     * @param $data
     * @param $attribute
     * @param $type
     * @return number|string
     */
    public static function summary($data, $attribute, $type)
    {
        $data = ArrayHelper::getColumn($data, $attribute);
        switch ($type) {
            case self::SUMMARY_SUM:
                return array_sum($data);
            case self::SUMMARY_COUNT:
                return count($data);
            case self::SUMMARY_AVG:
                return count($data) > 0 ? array_sum($data) / count($data) : null;
            case self::SUMMARY_MAX:
                return max($data);
            case self::SUMMARY_MIN:
                return min($data);
        }
        return '';
    }
}