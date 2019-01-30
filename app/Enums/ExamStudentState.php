<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 9:18 AM
 */

namespace App\Enums;


class ExamStudentState
{
    const NOT_FINISHED = 1;
    const FINISHED = 2;

    public static function getState($key) {
        switch ($key) {
            case self::NOT_FINISHED: return "غير منتهي"; break;
            case self::FINISHED: return "منتهي"; break;
        }

        return "";
    }
}