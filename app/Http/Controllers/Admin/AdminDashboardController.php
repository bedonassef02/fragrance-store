<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(\App\Services\DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $recentOrders = $this->dashboardService->getRecentOrders();
        $topProducts = $this->dashboardService->getTopSellingProducts();
        $lowStockItems = $this->dashboardService->getLowStockItems();
        $chartData = $this->dashboardService->getWeeklySalesChart();
        $analytics = $this->dashboardService->getAnalytics();
        $topViewedProducts = $this->dashboardService->getTopViewedProducts();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'lowStockItems', 'chartData', 'analytics', 'topViewedProducts'));
    }
}
