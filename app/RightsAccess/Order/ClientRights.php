<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 08.04.2017
 * Time: 12:21
 */

namespace App\RightsAccess\Nomenclature;


class ClientRights
{
    public function see($user)
    {
        if ($user->role->sys_name = 'manager')
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

}