<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 2/19/2019
 * Time: 1:41 PM
 */

namespace App\Enums;


class QuestionCorrectionState
{
    const UNCORRECTED = 0;
    const CORRECTED = 1;

    public static function getState($key)
    {
        switch ($key)
        {
            case self::UNCORRECTED: return "غير مصحح"; break;
            case self::CORRECTED: return "مصحح"; break;
        }

        return "";
    }
}