<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\Admin\AddOrderDepositRequest;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $orders = Order::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,returned,replaced,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function addDeposit(AddOrderDepositRequest $request, Order $order) // Modified type hint
    {
        // Removed inline validation and logic, delegated to OrderService
        $this->orderService->addDeposit(
            $order,
            $request->validated('deposit_amount'),
            $request->file('deposit_proof')
        );

        return back()->with('success', 'Deposit added successfully.');
    }
    public function deleteDeposit(Order $order)
    {
        $this->orderService->deleteDeposit($order);
        return back()->with('success', 'Deposit removed successfully.');
    }
}
