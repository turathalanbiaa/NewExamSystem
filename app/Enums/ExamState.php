<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 9:00 AM
 */

namespace App\Enums;


class ExamState
{
    const CLOSE = 1;
    const OPEN = 2;
    const END = 3;

    public static function getState($key) {
        switch ($key) {
            case self::CLOSE: return "مغلق"; break;
            case self::OPEN: return "مفتوح"; break;
            case self::END: return "منتهي"; break;
        }

        return "";
    }
}