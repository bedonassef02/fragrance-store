<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \App\Traits\Trackable;
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
        'payment_status',
        'transaction_id',
        'payment_gateway',
        'payment_meta',
        'status',
        'deposit_amount',
        'deposit_proof_path',
    ];

    protected $casts = [
        'payment_meta' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
