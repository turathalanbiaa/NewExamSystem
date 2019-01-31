<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 9:08 AM
 */

namespace App\Enums;


class QuestionType
{
    const TRUE_OR_FALSE = 1;
    const SINGLE_CHOICE = 2;
    const FILL_BLANK = 3;
    const EXPLAIN = 4;

    public static function getType($key)
    {
        switch ($key)
        {
            case self::TRUE_OR_FALSE: return "صح او خطأ"; break;
            case self::SINGLE_CHOICE: return "اختيارات"; break;
            case self::FILL_BLANK: return "فراغات"; break;
            case self::EXPLAIN: return "تعاريف او شرح"; break;
        }

        return "";
    }
}