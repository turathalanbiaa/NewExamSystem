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
            case self::BEGINNER: return "المستوى التمهيدي"; break;
            case self::FIRST: return "المستوى الاول"; break;
            case self::SECOND: return "المستوى الثاني"; break;
            case self::THIRD: return "المستوى الثالث"; break;
            case self::FOURTH: return "المستوى الرابع"; break;
            case self::FIFTH: return "المستوى الخامس"; break;
            case self::SIXTH: return "المستوى السادس"; break;

            default: return "";
        }
    }
}