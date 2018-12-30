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
    const LECTURER = 2;
    const COURSE = 3;
    const EXAM = 4;

    public static function getType($key) {
        switch ($key) {
            case 1: return "مدير"; break;
            case 2: return "استاذ";  break;
            case 3: return "المواد";  break;
            case 4: return "الامتحانات";  break;
        }

        return "";
    }
}