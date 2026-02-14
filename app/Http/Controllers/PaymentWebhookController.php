<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymobService;
use App\Services\FawryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    protected PaymobService $paymobService;
    protected FawryService $fawryService;

    public function __construct(PaymobService $paymobService, FawryService $fawryService)
    {
        $this->paymobService = $paymobService;
        $this->fawryService = $fawryService;
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

        return \Illuminate\Support\Facades\DB::transaction(function () use ($orderId, $transactionId, $success, $amountCents, $transactionData) {
            // Lock the order row for update
            $order = Order::where('id', $orderId)->lockForUpdate()->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Security: Idempotency check - don't process already-paid orders
            if ($order->payment_status === 'paid' && $order->transaction_id === $transactionId) {
                return response()->json(['success' => true, 'message' => 'Already processed']);
            }

            // Security: Verify payment amount matches order total
            if ($success && !$this->paymobService->verifyPaymentAmount($order, $amountCents)) {
                // ... logging omitted for brevity ...
                
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
                // TODO: Send payment confirmation email
                // TODO: Trigger inventory reservation if not already done
            }

            return response()->json(['success' => true]);
        });
    }

    /**
     * Handle Fawry webhook callback
     * 
     * Security: Verifies signature and updates order payment status
     */
    public function fawry(Request $request)
    {
        $data = $request->all();
        $receivedSignature = $data['messageSignature'] ?? '';

        Log::info('Fawry webhook received', [
            'ip' => $request->ip(),
            'has_signature' => !empty($receivedSignature),
            'order_status' => $data['orderStatus'] ?? 'unknown',
        ]);

        // Security: Verify webhook signature if Fawry is configured
        if ($this->fawryService->isConfigured()) {
            if (empty($receivedSignature)) {
                Log::warning('Fawry webhook: Missing signature', ['ip' => $request->ip()]);
                return response()->json(['error' => 'Missing signature'], 401);
            }

            if (!$this->fawryService->verifyWebhookSignature($data, $receivedSignature)) {
                Log::warning('Fawry webhook: Signature verification failed', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        // Process webhook data
        $result = $this->fawryService->processWebhook($data);
        $orderNumber = $result['order_number'];

        if (!$orderNumber) {
            Log::error('Fawry webhook: No merchant reference found');
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Find order by order number (merchantRefNum)
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::error('Fawry webhook: Order not found', ['order_number' => $orderNumber]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Idempotency check
        if ($order->payment_status === 'paid' && $result['status'] === 'PAID') {
            Log::info('Fawry webhook: Already processed', ['order_id' => $order->id]);
            return response()->json(['success' => true, 'message' => 'Already processed']);
        }

        // Map Fawry status to our payment status
        $paymentStatus = match (strtoupper($result['status'])) {
            'PAID' => 'paid',
            'NEW' => 'awaiting_payment',
            'UNPAID' => 'awaiting_payment',
            'EXPIRED' => 'expired',
            'CANCELED' => 'cancelled',
            'REFUNDED' => 'refunded',
            'PARTIAL_REFUNDED' => 'partially_refunded',
            default => 'failed',
        };

        // Update order
        $order->update([
            'payment_status' => $paymentStatus,
            'transaction_id' => $result['fawry_reference'],
            'payment_meta' => array_merge($order->payment_meta ?? [], [
                'fawry_reference' => $result['fawry_reference'],
                'fawry_status' => $result['status'],
                'fawry_amount' => $result['amount'],
                'processed_at' => now()->toIso8601String(),
            ]),
        ]);

        // If payment successful, confirm order
        if ($paymentStatus === 'paid') {
            $order->update(['status' => 'confirmed']);
            Log::info('Fawry payment successful', [
                'order_id' => $order->id,
                'fawry_ref' => $result['fawry_reference'],
                'amount' => $result['amount'],
            ]);
        } else {
            Log::info('Fawry payment status update', [
                'order_id' => $order->id,
                'status' => $paymentStatus,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
