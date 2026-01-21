<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function getAll(int $perPage = 10)
    {
        return Coupon::latest()->paginate($perPage);
    }

    public function create(array $data): Coupon
    {
        $data['code'] = strtoupper($data['code']); // Force uppercase
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): bool
    {
        $data['code'] = strtoupper($data['code']);
        return $coupon->update($data);
    }

    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete();
    }
}
