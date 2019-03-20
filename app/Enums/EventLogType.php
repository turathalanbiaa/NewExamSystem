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
    const PROFILE = 0;
    const ADMIN = 1;
    const LECTURER = 2;
    const COURSE = 3;
    const ASSESSMENT = 4;
    const EXAM = 5;
    const QUESTION = 6;
    const BRANCH = 7;


    public static function getType($key) {
        switch ($key) {
            case self::PROFILE: return "الملف الشخصي"; break;
            case self::ADMIN: return "مدير"; break;
            case self::LECTURER: return "استاذ";  break;
            case self::COURSE: return "مادة";  break;
            case self::ASSESSMENT: return "التقييم";  break;
            case self::EXAM: return "امتحان";  break;
            case self::QUESTION: return "سؤال";  break;
            case self::BRANCH: return "فرع من سؤال";  break;
        }

        return "";
    }
}