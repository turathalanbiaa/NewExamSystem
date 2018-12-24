<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/24/2018
 * Time: 10:00 AM
 */

namespace App\Enums;


class LecturerState
{
    const OPEN = 1;
    const CLOSE = 2;

    public static function getState($key) {
        switch ($key) {
            case 1: return "مفتوح"; break;
            case 2: return "مغلق";  break;
        }

        return "";
    }
}