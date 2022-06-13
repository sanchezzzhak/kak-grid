<?php

namespace kak\widgets\grid\helpers;

/**
 * Class GridHelper
 * @package kak\widgets\grid\helpers
 */
class GridHelper
{
    public const SUMMARY_SUM = 'sum';
    public const SUMMARY_COUNT = 'count';
    public const SUMMARY_AVG = 'avg';
    public const SUMMARY_MAX = 'max';
    public const SUMMARY_MIN = 'min';

    /**
     * @param $data
     * @param $attribute
     * @param $type
     * @return number|string
     */
    public static function summary($data, $attribute, $type)
    {
        $data = $data[$attribute] ?? [];
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