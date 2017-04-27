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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property  user_id
 */
class OrderModel extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Атрибуты, которые должны быть преобразованы к базовым типам.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean',
        'done' => 'boolean',
    ];

    public function materialStrings()
    {
        return $this->hasMany(OrdersMaterialValueModel::class)->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}