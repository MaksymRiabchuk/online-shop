<?php

namespace App\Enums;

class ProductReviewStatuses
{
    const CONFIRMED = 10;
    const WAITING = 11;
    const DECLINED = 12;

    public static $list = [
        self::CONFIRMED => 'Confirmed',
        self::WAITING => 'Waiting',
        self::DECLINED => 'Declined',
    ];

    public static function listData()
    {
        return static::$list;
    }

    public static function getLabel($value)
    {
        $list = static::$list;
        if (isset($list[$value])) {
            return $list[$value];
        }
        return null;
    }
}
