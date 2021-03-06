<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 21.04.2017
 * Time: 15:46
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrdersMaterialValueModel extends Model
{
    protected $table = 'ordersMaterialValues';
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(OrderModel::class);
    }

    public function materialValue()
    {
        return $this->belongsTo(MaterialValue::class, 'material_value_id', 'id');
    }
}