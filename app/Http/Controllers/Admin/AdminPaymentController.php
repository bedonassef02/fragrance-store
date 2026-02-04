<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    /**
     * Display payment settings page
     */
    public function index()
    {
        // Get recent orders with online payments
        $recentPayments = Order::whereIn('payment_method', ['card', 'wallet', 'fawry'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.payments.index', compact('recentPayments'));
    }
}
