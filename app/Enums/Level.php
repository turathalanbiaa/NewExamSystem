<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 1/1/2019
 * Time: 1:22 PM
 */

namespace App\Enums;


class Level
{
    const BEGINNER = 1;
    const FIRST = 2;
    const SECOND = 3;
    const THIRD = 4;
    const FOURTH = 5;
    const FIFTH = 6;
    const SIXTH = 7;

    public static function get($level)
    {
        switch ($level)
        {
            case 1: return "المستوى التمهيدي"; break;
            case 2: return "المستوى الاول"; break;
            case 3: return "المستوى الثاني"; break;
            case 4: return "المستوى الثالث"; break;
            case 5: return "المستوى الرابع"; break;
            case 6: return "المستوى الخامس"; break;
            case 7: return "المستوى السادس"; break;

            default: return "";
        }
    }
}