<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 21.04.2017
 * Time: 15:45
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    public $timestamps = false;

    public function materialValues()
    {
        return $this->hasMany(OrdersMaterialValueModel::class);
    }

    public function counterparty()
    {
        return $this->belongsTo(CounterpartyModel::class);
    }
}