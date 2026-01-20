<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereHas('variants', function($q) {
                $q->where('quantity', '<', 5);
            })->count(),
            'average_order_value' => Order::where('status', '!=', 'cancelled')->count() > 0 
                ? Order::where('status', '!=', 'cancelled')->avg('total_amount') 
                : 0,
        ];

        $recentOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
