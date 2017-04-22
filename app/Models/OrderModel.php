<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 21.04.2017
 * Time: 15:45
 */

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    public $timestamps = false;

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function materialValues()
    {
        return $this->hasMany(OrdersMaterialValueModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}