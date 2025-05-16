<?php

namespace App\Models;

use Database\Factories\OrdersModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'plan_id',
        'training_id',
        'total_price_orders'
    ];

    protected static function newFactory()
    {
        return OrdersModelFactory::new();
    }
}
