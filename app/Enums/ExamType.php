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
    const MONTHLY = 1;
    const DAILY = 2;
    const EVALUATION = 3;

    public static function getType($key) {
        switch ($key) {
            case 1: return "شهري"; break;
            case 2: return "يومي"; break;
            case 3: return "تقييم (نشاط)"; break;
        }

        return "";
    }
}