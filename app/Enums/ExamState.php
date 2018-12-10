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
    const OPEN = 1;
    const CLOSE = 2;
    const END = 3;

    public static function getState($key) {
        switch ($key) {
            case 1: return "مفتوح"; break;
            case 2: return "مغلق"; break;
            case 3: return "منتهي"; break;
        }
        return "";
    }
}