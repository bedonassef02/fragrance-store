<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FawryService
{
    protected string $baseUrl;
    protected ?string $merchantCode;
    protected ?string $secureKey;
    protected bool $isStaging;

    public function __construct()
    {
        $this->isStaging = config('fawry.staging', true);
        $this->baseUrl = config('fawry.base_url');
        $this->merchantCode = config('fawry.merchant_code');
        $this->secureKey = config('fawry.secure_key');
    }

    /**
     * Check if Fawry is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->merchantCode) && !empty($this->secureKey);
    }

    /**
     * Create a PayAtFawry charge and get reference number
     * 
     * @param Order $order The order to create payment for
     * @return array{success: bool, reference_number?: string, error?: string}
     */
    public function createPayAtFawryCharge(Order $order): array
    {
        // Security: Ensure Fawry is configured
        if (!$this->isConfigured()) {
            Log::error('FawryPay not configured: Missing merchant credentials');
            return [
                'success' => false,
                'error' => 'Fawry payment not available. Please contact support.',
            ];
        }

        try {
            // Build charge items from order
            $chargeItems = $order->items->map(function ($item) {
                return [
                    'itemId' => (string) $item->product_id,
                    'description' => substr(strip_tags($item->product_name), 0, 100),
                    'price' => number_format($item->unit_price, 2, '.', ''),
                    'quantity' => (int) $item->quantity,
                ];
            })->toArray();

            // Prepare amount in decimal format
            $amount = number_format($order->total_amount, 2, '.', '');

            // Generate unique merchant reference number
            $merchantRefNum = $order->order_number;

            // Calculate payment expiry (in milliseconds from epoch)
            $expiryHours = config('fawry.payment_expiry_hours', 48);
            $paymentExpiry = (time() + ($expiryHours * 3600)) * 1000;

            // Generate signature
            $signature = $this->generateChargeSignature(
                $merchantRefNum,
                $order->phone ?? '',
                'PAYATFAWRY',
                $amount
            );

            $payload = [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'customerMobile' => preg_replace('/[^0-9]/', '', $order->phone ?? ''),
                'customerEmail' => $order->email,
                'customerName' => trim($order->first_name . ' ' . $order->last_name),
                'customerProfileId' => $order->user_id ?? $order->email,
                'paymentMethod' => 'PAYATFAWRY',
                'amount' => $amount,
                'currencyCode' => 'EGP',
                'chargeItems' => $chargeItems,
                'signature' => $signature,
                'paymentExpiry' => $paymentExpiry,
                'language' => config('fawry.language', 'en-gb'),
                'description' => "Order #{$order->order_number}",
            ];

            // Add webhook URL if configured
            $webhookUrl = config('fawry.webhook_url');
            if (!empty($webhookUrl)) {
                $payload['orderWebHookUrl'] = $webhookUrl;
            }

            Log::info('Sending FawryPay charge request', [
                'order_id' => $order->id,
                'merchant_ref' => $merchantRefNum,
                'amount' => $amount,
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/ECommerceWeb/Fawry/payments/charge', $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Check for success status code
                if (isset($data['statusCode']) && $data['statusCode'] == 200) {
                    Log::info('FawryPay reference number generated', [
                        'order_id' => $order->id,
                        'reference_number' => $data['referenceNumber'] ?? null,
                    ]);

                    return [
                        'success' => true,
                        'reference_number' => $data['referenceNumber'],
                        'merchant_ref_number' => $data['merchantRefNumber'] ?? $merchantRefNum,
                        'expiry_date' => $data['expiryDate'] ?? null,
                    ];
                }

                // Handle Fawry error response
                Log::error('FawryPay charge failed', [
                    'response' => $data,
                    'order_id' => $order->id,
                ]);

                return [
                    'success' => false,
                    'error' => $data['statusDescription'] ?? 'Failed to generate Fawry reference',
                ];
            }

            Log::error('FawryPay API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order_id' => $order->id,
            ]);

            return [
                'success' => false,
                'error' => 'Fawry service temporarily unavailable',
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('FawryPay connection timeout', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Fawry service temporarily unavailable. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('FawryPay exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Payment service unavailable',
            ];
        }
    }

    /**
     * Generate signature for charge request
     * 
     * Format: SHA256(merchantCode + merchantRefNum + customerProfileId + paymentMethod + amount + secureKey)
     */
    protected function generateChargeSignature(
        string $merchantRefNum,
        string $customerProfileId,
        string $paymentMethod,
        string $amount
    ): string {
        $data = $this->merchantCode 
            . $merchantRefNum 
            . ($customerProfileId ?: '')
            . $paymentMethod 
            . $amount 
            . $this->secureKey;

        return hash('sha256', $data);
    }

    /**
     * Verify webhook callback signature
     * 
     * Fawry sends: fawryRefNumber + merchantRefNum + paymentAmount + orderAmount + orderStatus + paymentMethod + paymentRefrenceNumber + secureKey
     */
    public function verifyWebhookSignature(array $data, string $receivedSignature): bool
    {
        if (empty($this->secureKey)) {
            Log::error('FawryPay secure key not configured for webhook verification');
            return false;
        }

        // Build signature string according to Fawry documentation
        $signatureData = ($data['fawryRefNumber'] ?? '')
            . ($data['merchantRefNum'] ?? '')
            . number_format((float)($data['paymentAmount'] ?? 0), 2, '.', '')
            . number_format((float)($data['orderAmount'] ?? 0), 2, '.', '')
            . ($data['orderStatus'] ?? '')
            . ($data['paymentMethod'] ?? '')
            . ($data['paymentRefrenceNumber'] ?? '')
            . $this->secureKey;

        $calculatedSignature = hash('sha256', $signatureData);

        // Use constant-time comparison to prevent timing attacks
        return hash_equals($calculatedSignature, $receivedSignature);
    }

    /**
     * Process Fawry webhook callback
     */
    public function processWebhook(array $data): array
    {
        $orderStatus = strtoupper($data['orderStatus'] ?? '');
        $merchantRefNum = $data['merchantRefNum'] ?? null;
        $fawryRefNumber = $data['fawryRefNumber'] ?? null;
        $paymentAmount = (float) ($data['paymentAmount'] ?? 0);

        return [
            'success' => $orderStatus === 'PAID',
            'order_number' => $merchantRefNum,
            'fawry_reference' => $fawryRefNumber,
            'amount' => $paymentAmount,
            'status' => $orderStatus,
            'payment_method' => $data['paymentMethod'] ?? 'PAYATFAWRY',
        ];
    }

    /**
     * Get payment status from Fawry
     */
    public function getPaymentStatus(string $merchantRefNum): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Fawry not configured'];
        }

        try {
            $signature = hash('sha256', $this->merchantCode . $merchantRefNum . $this->secureKey);

            $response = Http::timeout(30)
                ->get($this->baseUrl . '/ECommerceWeb/Fawry/payments/status/v2', [
                    'merchantCode' => $this->merchantCode,
                    'merchantRefNumber' => $merchantRefNum,
                    'signature' => $signature,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['orderStatus'] ?? 'UNKNOWN',
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get payment status',
            ];
        } catch (\Exception $e) {
            Log::error('FawryPay status check failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Service unavailable',
            ];
        }
    }
}
