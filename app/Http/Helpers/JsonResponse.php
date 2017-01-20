<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 19.01.2017
 * Time: 23:09
 */

namespace App\Http\Helpers;


class JsonResponse
{
    /**
     * @param int $status
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    static function gen($status, $data = null)
    {
        return response()->json([
            'notifications' => Notifications::get(),
            'data' => $data
        ], $status);
    }
}