<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
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

    public function addDeposit(Request $request, Order $order)
    {
        $validated = $request->validate([
            'deposit_amount' => 'required|numeric|min:0',
            'deposit_proof' => 'required|image|max:2048', // 2MB max
        ]);

        $path = $request->file('deposit_proof')->store('deposits', 'public');

        $order->update([
            'deposit_amount' => $validated['deposit_amount'],
            'deposit_proof_path' => $path,
        ]);

        return back()->with('success', 'Deposit added successfully.');
    }
}
