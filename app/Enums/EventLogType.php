<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/18/2018
 * Time: 8:38 AM
 */

namespace App\Enums;


class EventLogType
{
    const ADMIN = 1;
    const COURSE = 2;
    const lECTURER = 3;
    const ROOT_EXAM = 4;
    const EXAM = 5;
    const ROOT_QUESTION = 6;
    const QUESTION = 7;

    public static function getType($key) {
        switch ($key) {
            case 1: return "1"; break;
            case 2: return "2";  break;
            case 3: return "3";  break;
            case 4: return "4";  break;
            case 5: return "5";  break;
            case 6: return "6";  break;
            case 7: return "7";  break;
        }
        return "";
    }
}