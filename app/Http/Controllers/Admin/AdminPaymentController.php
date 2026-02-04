<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\PaymobService;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    protected PaymobService $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * Display payment settings page
     */
    public function index()
    {
        // Get payment method statuses
        $paymentMethods = $this->paymobService->getPaymentMethodStatuses();

        // Get recent orders with online payments
        $recentPayments = Order::whereIn('payment_method', ['card', 'wallet', 'fawry'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.payments.index', compact('paymentMethods', 'recentPayments'));
    }

    /**
     * Toggle a payment method on/off
     */
    public function toggle(\App\Http\Requests\Admin\TogglePaymentMethodRequest $request)
    {
        $method = $request->input('method');
        $enabled = $request->input('enabled');

        // Save to database settings
        Setting::setValue("payment_{$method}_enabled", $enabled ? 'true' : 'false');

        return response()->json([
            'success' => true,
            'message' => ucfirst($method) . ' payment ' . ($enabled ? 'enabled' : 'disabled'),
        ]);
    }
}
