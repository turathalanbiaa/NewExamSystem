<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 2/19/2019
 * Time: 1:56 PM
 */

namespace App\Enums;


class AnswerCorrectionState
{
    const UNCORRECTED = 1;
    const CORRECTED = 2;
    const RE_CORRECTED = 3;

    public static function getState($key)
    {
        switch ($key)
        {
            case self::UNCORRECTED: return "غير مصحح"; break;
            case self::CORRECTED: return "مصحح"; break;
            case self::RE_CORRECTED: return "يعتبر صحيح"; break;
        }

        return "";
    }
}