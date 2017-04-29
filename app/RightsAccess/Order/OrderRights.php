<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 08.04.2017
 * Time: 12:21
 */

namespace App\RightsAccess\Order;


class OrderRights
{
    public function see($user)
    {
        if ($user->role->sys_name == 'manager' ||
            $user->role->sys_name == 'wh_head' ||
            $user->role->sys_name == 'wh_worker'
        )

            return true;
        return false;
    }

    public function add($user)
    {
        if ($user->role->sys_name == 'manager')
            return true;
        return false;
    }

    public function edit($user)
    {
        if ($user->role->sys_name == 'manager' ||
            $user->role->sys_name == 'wh_head' ||
            $user->role->sys_name == 'wh_worker'
        )
            return true;
        return false;
    }

    public function editStrings($user)
    {
        if ($user->role->sys_name == 'manager')
            return true;
        return false;
    }

    public function delete($user)
    {
        if ($user->role->sys_name == 'manager')
            return true;
        return false;
    }

    public function confirm($user)
    {
        if ($user->role->sys_name == 'manager')
            return true;
        return false;
    }

    public function close($user)
    {
        if ($user->role->sys_name == 'wh_head')
            return true;
        return false;
    }

    public function equip($user)
    {
        if ($user->role->sys_name == 'wh_worker')
            return true;
        return false;
    }



}