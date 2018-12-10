<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/9/2018
 * Time: 9:08 AM
 */

namespace App\Enums;


class RootQuestionType
{
    const TRUE_OR_FALSE = 1;
    const SINGLE_CHOICE = 2;
    const FILL_BLANK = 3;
    const EXPLAIN = 4;

    public static function getType($key) {
        switch ($key) {
            case 1: return "صح أو خطأ"; break;
            case 2: return "اختيارات"; break;
            case 3: return "فراغات او تعاريف"; break;
            case 4: return "اشرح او وضح"; break;
        }
        return "";
    }
}