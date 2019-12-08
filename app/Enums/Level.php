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
            case self::BEGINNER: return "المرحلة التمهيدية"; break;
            case self::FIRST: return "المرحلة الاولى المستوى الاول"; break;
            case self::SECOND: return "المرحلة الاولى المستوى الثاني"; break;
            case self::THIRD: return "المرحلة الثانية المستوى الاول"; break;
            case self::FOURTH: return "المرحلة الثانية المستوى الثاني"; break;
            case self::FIFTH: return "المرحلة الثالثة المستوى الاول"; break;
            case self::SIXTH: return "المرحلة الثالثة المستوى الثاني"; break;

            default: return "";
        }
    }
}
