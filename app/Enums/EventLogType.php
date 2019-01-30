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
    const QUESTION = 5;
    const BRANCH = 6;

    public static function getType($key) {
        switch ($key) {
            case self::ADMIN: return "مدير"; break;
            case self::LECTURER: return "استاذ";  break;
            case self::COURSE: return "مادة";  break;
            case self::EXAM: return "امتحان";  break;
            case self::QUESTION: return "سؤال";  break;
            case self::BRANCH: return "فرع من سؤال";  break;
        }

        return "";
    }
}