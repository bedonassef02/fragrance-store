<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * Get all unique customers aggregated by email.
     *
     * @param string|null $search
     * @param string|null $sort
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCustomers($search = null, $sort = 'latest')
    {
        $query = Order::query()
            ->selectRaw('
                email, 
                MAX(first_name) as first_name, 
                MAX(last_name) as last_name, 
                MAX(phone) as phone, 
                COUNT(*) as total_orders, 
                SUM(total_amount) as total_spent, 
                MAX(created_at) as last_order_date
            ')
            ->groupBy('email');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sorting logic based on aggregated columns
        switch ($sort) {
            case 'oldest':
                $query->orderBy('last_order_date', 'asc');
                break;
            case 'highest_spent':
                $query->orderByDesc('total_spent');
                break;
            case 'most_orders':
                $query->orderByDesc('total_orders');
                break;
            case 'latest':
            default:
                $query->orderByDesc('last_order_date');
                break;
        }

        return $query->paginate(15);
    }

    /**
     * Get customer details and history by email.
     *
     * @param string $email
     * @return array
     */
    public function getCustomerDetails($email)
    {
        // Get aggregate stats
        $stats = Order::where('email', $email)
            ->selectRaw('
                email,
                MAX(first_name) as first_name,
                MAX(last_name) as last_name,
                MAX(phone) as phone,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_spent,
                MAX(created_at) as last_order_date,
                MIN(created_at) as first_order_date
            ')
            ->groupBy('email')
            ->firstOrFail();

        // Get order history
        $orders = Order::where('email', $email)
            ->with(['items']) // Load items if needed for summary
            ->orderByDesc('created_at')
            ->get();

        return [
            'stats' => $stats,
            'orders' => $orders
        ];
    }
}
