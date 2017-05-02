<?php

/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 02.05.2017
 * Time: 11:56
 */
class WHBaseRights
{
    public function seeBalance($user)
    {
        if ($user->role->sys_name == 'wh_head')
            return true;
        return false;
    }
}