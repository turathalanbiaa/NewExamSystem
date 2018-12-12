<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 8:41 AM
 */

namespace App\Enums;


class AdminType
{

    const MANAGER = 1;
    const LECTURER = 2;

    public static function getType($key) {
        switch ($key) {
            case 1: return "مدير"; break;
            case 2: return "استاذ"; break;
        }
        return "";
    }
}