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
    public $timestamps = false;

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = ['date'];

    public function order()
    {
        return $this->belongsTo(OrderModel::class);
    }
}