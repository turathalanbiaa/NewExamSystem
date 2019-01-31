<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 8:47 AM
 */

namespace App\Enums;


class ExamType
{
    const FIRST_MONTH = 1;
    const SECOND_MONTH = 2;
    const FINAL_FIRST_ROLE = 3;
    const FINAL_SECOND_ROLE = 4;

    public static function getType($key) {
        switch ($key) {
            case self::FIRST_MONTH: return "الشهر الاول"; break;
            case self::SECOND_MONTH: return "الشهر الثاني"; break;
            case self::FINAL_FIRST_ROLE: return "نهائي (الدور الاول)"; break;
            case self::FINAL_SECOND_ROLE: return "نهائي (الدور الثاني)"; break;
        }

        return "";
    }
}