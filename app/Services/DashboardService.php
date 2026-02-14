<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\PageVisit;
use App\Models\ProductView;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats()
    {
        $currentMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
            
        $lastMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total_amount');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        return [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'revenue_growth' => $revenueGrowth,
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'total_products' => Product::count(),
            'low_stock_count' => ProductVariant::where('quantity', '<', 5)->count(),
            'average_order_value' => Order::where('status', '!=', 'cancelled')->avg('total_amount') ?? 0,
        ];
    }

    public function getRecentOrders($limit = 5)
    {
        return Order::with('items')->latest()->take($limit)->get();
    }

    public function getTopSellingProducts($limit = 5)
    {
        return OrderItem::select('product_name', DB::raw('sum(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get();
    }

    public function getLowStockItems($limit = 5)
    {
        return ProductVariant::with('product', 'color')
            ->where('quantity', '<', 5)
            ->take($limit)
            ->get();
    }

    public function getWeeklySalesChart()
    {
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

        return $chartData;
    }

    public function getAnalytics()
    {
        return [
            'visits_today' => PageVisit::whereDate('created_at', today())->count(),
            'total_visits' => PageVisit::count(),
            'unique_visitors_today' => PageVisit::whereDate('created_at', today())->distinct('ip_address')->count('ip_address'),
        ];
    }

    public function getTopViewedProducts($limit = 5)
    {
        return ProductView::select('product_id', DB::raw('count(*) as views'))
            ->with(['product' => function($q) { $q->select('id', 'name', 'image', 'price'); }])
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->take($limit)
            ->get();
    }
}
