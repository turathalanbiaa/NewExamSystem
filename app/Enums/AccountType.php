<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 8:41 AM
 */

namespace App\Enums;


class AccountType
{

    const MANAGER = 1;
    const LECTURER = 2;

    public static function getType($key) {
        switch ($key) {
            case self::MANAGER: return "مدير"; break;
            case self::LECTURER: return "استاذ"; break;
        }

        return "";
    }
}