<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'email',
        'first_name',
        'last_name',
        'address',
        'city',
        'phone',
        'total_amount',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'payment_method',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
