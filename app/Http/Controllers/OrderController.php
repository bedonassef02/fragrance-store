<?php 
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($orderNumber)
    {
        $order = Order::with('items.variant.product')->where('order_number', $orderNumber)->firstOrFail();
        return view('orders.show', compact('order'));
    }
}
