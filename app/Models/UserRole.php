<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.04.2017
 * Time: 13:00
 */

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'users_roles';
    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}