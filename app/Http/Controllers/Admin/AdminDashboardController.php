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
        // 1. Revenue & Growth
        $currentMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
            
        $lastMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total_amount');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'revenue_growth' => $revenueGrowth,
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'total_products' => Product::count(),
            'low_stock_count' => \App\Models\ProductVariant::where('quantity', '<', 5)->count(),
            'average_order_value' => Order::where('status', '!=', 'cancelled')->avg('total_amount') ?? 0,
        ];

        // 2. Recent Orders
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        // 3. Top Selling Products
        $topProducts = \App\Models\OrderItem::select('product_name', \Illuminate\Support\Facades\DB::raw('sum(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 4. Low Stock Items
        $lowStockItems = \App\Models\ProductVariant::with('product', 'color')
            ->where('quantity', '<', 5)
            ->take(5)
            ->get();

        // 5. Weekly Sales Chart Data
        $weeklySales = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function ($orders) {
                return $orders->sum('total_amount');
            });
            
        // Fill missing days with 0
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D');
            $chartData[$dayName] = $weeklySales[$date] ?? 0;
        }

        // 6. Analytics Data
        $analytics = [
            'visits_today' => \App\Models\PageVisit::whereDate('created_at', today())->count(),
            'total_visits' => \App\Models\PageVisit::count(),
            'unique_visitors_today' => \App\Models\PageVisit::whereDate('created_at', today())->distinct('ip_address')->count('ip_address'),
        ];

        // 7. Top Viewed Products
        $topViewedProducts = \App\Models\ProductView::select('product_id', \Illuminate\Support\Facades\DB::raw('count(*) as views'))
            ->with(['product' => function($q) { $q->select('id', 'name', 'image', 'price'); }])
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'lowStockItems', 'chartData', 'analytics', 'topViewedProducts'));
    }
}
