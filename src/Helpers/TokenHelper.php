<?php

namespace YiiContrib\Sms\Helpers;

class TokenHelper
{
    /**
     * @param int $length
     *
     * @return string
     */
    public static function random($length = 4)
    {
        $chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        shuffle($chars);
        
        return implode(array_slice($chars, 0, $length));
    }
}