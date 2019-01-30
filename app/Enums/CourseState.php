<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 8:32 AM
 */

namespace App\Enums;


class CourseState
{
    const OPEN = 1;
    const CLOSE = 2;

    public static function getState($key) {
        switch ($key) {
            case self::OPEN: return "مفتوح"; break;
            case self::CLOSE: return "مغلق";  break;
        }

        return "";
    }
}