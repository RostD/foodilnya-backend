<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 19.01.2017
 * Time: 23:12
 */

namespace App\Http\Helpers;


class Notifications
{
    static private $notofocations = [];

    /**
     * @param int $level 0,1,2,3
     * @param string $messForUser
     * @param string $messForDev
     */
    static function add(string $messForUser, int $level = 0, string $messForDev = null)
    {
        self::$notofocations[] = [
            'level' => $level,
            'message' => $messForUser,
            'devInfo' => $messForDev
        ];
    }

    static function get()
    {
        return self::$notofocations;
    }
}