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
    const ROOT_EXAM = 4;
    const EXAM = 5;
    const ROOT_QUESTION = 6;
    const QUESTION = 7;

    public static function getType($key) {
        switch ($key) {
            case 1: return "مدير"; break;
            case 2: return "استاذ";  break;
            case 3: return "المواد";  break;
            case 4: return "الامتحانات";  break;
            case 5: return "الامتحانات";  break;
            case 6: return "اسئلة";  break;
            case 7: return "اسئلة";  break;
        }

        return "";
    }
}