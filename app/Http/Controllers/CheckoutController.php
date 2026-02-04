<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymobService;
use App\Services\FawryService;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymobService $paymobService;
    protected FawryService $fawryService;

    public function __construct(
        CartService $cartService, 
        OrderService $orderService, 
        PaymobService $paymobService,
        FawryService $fawryService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymobService = $paymobService;
        $this->fawryService = $fawryService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $subtotal = $this->cartService->getSubtotal();
        $shipping = $this->cartService->getShipping(); 
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();
        $paymentMethods = $this->paymobService->getPaymentMethods();

        return view('checkout.index', compact('cart', 'subtotal', 'shipping', 'discount', 'total', 'paymentMethods'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $paymentMethod = $request->input('payment_method', 'cod');
            
            // Determine payment gateway
            $paymentGateway = match($paymentMethod) {
                'cod' => null,
                'fawry' => 'fawry',
                default => 'paymob',
            };

            // Create order with pending payment status
            $orderData = array_merge($request->validated(), [
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'awaiting_payment',
                'payment_gateway' => $paymentGateway,
            ]);

            $order = $this->orderService->createOrder($orderData);

            // If COD, redirect to success
            if ($paymentMethod === 'cod') {
                return redirect()->route('checkout.success', $order->order_number);
            }

            // For Fawry, generate reference code and show instructions
            if ($paymentMethod === 'fawry') {
                return $this->processFawryPayment($order);
            }

            // For card/wallet, redirect to Paymob
            return $this->processPayment($order, $paymentMethod);

        } catch (\Exception $e) {
            return back()->with('error', 'Order processing failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Process online payment
     */
    public function processPayment(Order $order, string $paymentMethod = 'card')
    {
        $intention = $this->paymobService->createPaymentIntention($order, $paymentMethod);

        if (!$intention['success']) {
            // Payment initialization failed, mark order and show error
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('checkout.payment.failed', $order->id)
                ->with('error', $intention['error'] ?? 'Payment initialization failed');
        }

        // Store intention data
        $order->update([
            'payment_meta' => array_merge($order->payment_meta ?? [], [
                'intention_id' => $intention['intention_id'],
                'client_secret' => $intention['client_secret'],
            ])
        ]);

        // Redirect to Paymob checkout
        $checkoutUrl = $this->paymobService->getCheckoutUrl($intention['client_secret']);
        return redirect()->away($checkoutUrl);
    }

    /**
     * Process Fawry payment - call Fawry API for reference code
     */
    protected function processFawryPayment(Order $order)
    {
        // Call FawryPay API to get real reference number
        $result = $this->fawryService->createPayAtFawryCharge($order);

        if (!$result['success']) {
            // Fawry API call failed
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('checkout.payment.failed', $order->id)
                ->with('error', $result['error'] ?? 'Failed to generate Fawry reference');
        }

        // Store reference code from Fawry API
        $order->update([
            'payment_meta' => [
                'fawry_reference' => $result['reference_number'],
                'fawry_merchant_ref' => $result['merchant_ref_number'] ?? $order->order_number,
                'fawry_expires_at' => $result['expiry_date'] ?? now()->addHours(48)->toIso8601String(),
            ]
        ]);

        return view('checkout.fawry-reference', [
            'order' => $order,
            'referenceCode' => $result['reference_number'],
            'expiresAt' => $result['expiry_date'] ? \Carbon\Carbon::parse($result['expiry_date']) : now()->addHours(48),
            'totalAmount' => $order->total_amount,
        ]);
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Order $order)
    {
        // Double-check payment status (webhook should have updated it)
        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order->order_number);
        }

        // If not yet updated by webhook, show pending state
        return view('checkout.payment-pending', compact('order'));
    }

    /**
     * Payment failed callback
     */
    public function paymentFailed(Order $order)
    {
        return view('checkout.payment-failed', compact('order'));
    }

    public function success($orderNumber)
    {
        return view('checkout.success', compact('orderNumber'));
    }
}
