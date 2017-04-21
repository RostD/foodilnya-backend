<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 21.04.2017
 * Time: 15:41
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CounterpartyModel extends Model
{
    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(OrderModel::class);
    }

}