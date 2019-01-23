<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 9:18 AM
 */

namespace App\Enums;


class EnrollmentState
{
    const NOT_FINISHED = 1;
    const FINISHED = 2;

    public static function getState($key) {
        switch ($key) {
            case 1: return "غير منتهي"; break;
            case 2: return "منتهي"; break;
        }

        return "";
    }
}