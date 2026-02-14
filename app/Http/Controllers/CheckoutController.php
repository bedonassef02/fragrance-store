<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymobService;
use App\Services\FawryService;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

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
                // Queue Order Confirmation Email
                Mail::to($order->email)->queue(new OrderConfirmation($order));
                
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

        // Log pending transaction attempt
        $this->orderService->logPaymentTransaction(
            $order,
            'paymob',
            $intention['intention_id'] ?? 'pending-' . uniqid(),
            'pending',
            ['method' => $paymentMethod, 'intention' => $intention]
        );

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
    public function paymentSuccess(Request $request, Order $order)
    {
        // Double-check payment status (webhook should have updated it)
        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order->order_number);
        }

        // Check if we have a transaction ID in the URL to verify
        $transactionId = $request->query('id');
        
        if ($transactionId && $order->payment_status !== 'paid') {
            // Attempt to verify directly with Paymob
            $verification = $this->paymobService->getTransaction($transactionId);
            
            if ($verification['success']) {
                $data = $verification['data'];
                $success = $data['success'] ?? false;
                $isPaid = $success && !($data['pending'] ?? true); // Ensure not pending
                
                if ($isPaid) {
                     $amountCents = (int) ($data['amount_cents'] ?? 0);
                     if ($this->paymobService->verifyPaymentAmount($order, $amountCents)) {
                          \Illuminate\Support\Facades\DB::transaction(function () use ($order, $transactionId, $amountCents, $data) {
                              // Lock the order to prevent race with webhook
                              $order = Order::where('id', $order->id)->lockForUpdate()->first();
                              
                              if ($order->payment_status !== 'paid') {
                                  $order->update([
                                    'payment_status' => 'paid',
                                    'status' => 'confirmed',
                                    'transaction_id' => $transactionId,
                                    'payment_gateway' => 'paymob',
                                    'payment_meta' => array_merge($order->payment_meta ?? [], [
                                        'transaction_id' => $transactionId,
                                        'verified_at' => now()->toIso8601String(),
                                        'source' => 'manual_verification'
                                    ])
                                ]);

                                // Queue Order Confirmation Email
                                Mail::to($order->email)->queue(new OrderConfirmation($order));

                                // Log to strict ledger
                                $this->orderService->logPaymentTransaction(
                                    $order,
                                    'paymob',
                                    $transactionId,
                                    'paid',
                                    $data,
                                    $amountCents / 100,
                                    $data['currency'] ?? 'EGP'
                                );
                              }
                          });
                        return redirect()->route('checkout.success', $order->order_number);
                     }
                }
            }
        }

        // If still not paid, show pending
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
