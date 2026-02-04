<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    protected PaymobService $paymobService;

    public function __construct(PaymobService $paymobService)
    {
        $this->paymobService = $paymobService;
    }

    /**
     * Handle Paymob webhook callback
     * 
     * Security considerations:
     * - HMAC verification is MANDATORY for production
     * - Amount verification prevents manipulation
     * - Idempotency check prevents duplicate processing
     */
    public function paymob(Request $request)
    {
        $data = $request->all();
        $hmac = $request->header('hmac') ?? $request->query('hmac');

        // Log incoming webhook (exclude sensitive data in production)
        Log::info('Paymob webhook received', [
            'ip' => $request->ip(),
            'has_hmac' => !empty($hmac),
        ]);

        // Security: HMAC verification is REQUIRED
        if (empty($hmac)) {
            Log::warning('Paymob webhook: Missing HMAC signature', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Missing signature'], 401);
        }

        // Extract transaction data from webhook payload
        $transactionData = $data['obj'] ?? $data;

        // Verify HMAC signature
        if (!$this->paymobService->verifyHmac($transactionData, $hmac)) {
            Log::warning('Paymob HMAC verification failed', [
                'ip' => $request->ip(),
                'received_hmac' => substr($hmac, 0, 20) . '...',
            ]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Extract order identifiers
        $success = $transactionData['success'] ?? false;
        $transactionId = $transactionData['id'] ?? null;
        $amountCents = (int) ($transactionData['amount_cents'] ?? 0);
        
        // Try multiple sources for order ID
        $orderId = $transactionData['order']['merchant_order_id'] ?? null;
        if (!$orderId && isset($transactionData['payment_key_claims']['extra']['order_id'])) {
            $orderId = $transactionData['payment_key_claims']['extra']['order_id'];
        }
        // Also try the extras field from intention
        if (!$orderId && isset($transactionData['order']['extras']['order_id'])) {
            $orderId = $transactionData['order']['extras']['order_id'];
        }

        if (!$orderId) {
            Log::error('Paymob webhook: No order ID found', [
                'transaction_id' => $transactionId,
            ]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Find the order - use integer cast for security
        $order = Order::find((int) $orderId);
        
        if (!$order) {
            Log::error('Paymob webhook: Order not found in database', ['order_id' => $orderId]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Security: Idempotency check - don't process already-paid orders
        if ($order->payment_status === 'paid' && $order->transaction_id === $transactionId) {
            Log::info('Paymob webhook: Duplicate callback ignored', [
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
            ]);
            return response()->json(['success' => true, 'message' => 'Already processed']);
        }

        // Security: Verify payment amount matches order total
        if ($success && !$this->paymobService->verifyPaymentAmount($order, $amountCents)) {
            Log::error('Paymob webhook: Amount mismatch', [
                'order_id' => $order->id,
                'expected' => (int) round($order->total_amount * 100),
                'received' => $amountCents,
            ]);
            
            // Still update order but flag for review
            $order->update([
                'payment_status' => 'review_required',
                'payment_meta' => [
                    'transaction_id' => $transactionId,
                    'amount_cents' => $amountCents,
                    'expected_cents' => (int) round($order->total_amount * 100),
                    'reason' => 'amount_mismatch',
                ],
            ]);
            
            return response()->json(['error' => 'Amount mismatch'], 400);
        }

        // Update order payment status
        $order->update([
            'payment_status' => $success ? 'paid' : 'failed',
            'transaction_id' => $transactionId,
            'payment_gateway' => 'paymob',
            'payment_meta' => [
                'transaction_id' => $transactionId,
                'amount_cents' => $amountCents,
                'currency' => $transactionData['currency'] ?? 'EGP',
                'source_type' => $transactionData['source_data']['type'] ?? null,
                'source_subtype' => $transactionData['source_data']['sub_type'] ?? null,
                'processed_at' => now()->toIso8601String(),
            ],
        ]);

        // Update order status if payment successful
        if ($success) {
            $order->update(['status' => 'confirmed']);
            Log::info('Payment successful', [
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'amount' => $amountCents / 100,
            ]);
            
            // TODO: Send payment confirmation email
            // TODO: Trigger inventory reservation if not already done
        } else {
            Log::info('Payment failed', [
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle Fawry webhook callback
     * 
     * TODO: Implement when Fawry API integration is added
     * For now, Fawry payments are manual reference codes
     */
    public function fawry(Request $request)
    {
        // TODO: Implement Fawry webhook handling
        // This would verify payment via Fawry's API callback
        Log::info('Fawry webhook received', $request->all());
        return response()->json(['success' => true]);
    }
}
