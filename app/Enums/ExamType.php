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
            case 1: return "الشهر الاول"; break;
            case 2: return "الشهر الثاني"; break;
            case 3: return "نهائي (الدور الاول)"; break;
            case 4: return "نهائي (الدور الثاني)"; break;
        }

        return "";
    }
}