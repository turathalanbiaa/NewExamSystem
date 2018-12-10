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
    const LECTURER = 1;
    const MANAGER = 2;

    public static function getType($key) {
        switch ($key) {
            case 1: return "استاذ"; break;
            case 2: return "مدير"; break;
        }
        return "";
    }
}