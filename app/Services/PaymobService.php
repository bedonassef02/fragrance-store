<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymobService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected ?string $secretKey;
    protected ?string $hmacSecret;

    public function __construct()
    {
        $this->baseUrl = config('paymob.base_url');
        $this->apiKey = config('paymob.api_key');
        $this->secretKey = config('paymob.secret_key');
        $this->hmacSecret = config('paymob.hmac_secret');
    }

    /**
     * Check if Paymob is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->secretKey) && !empty($this->hmacSecret) && !empty($this->apiKey);
    }

    /**
     * Create a payment intention for an order
     * 
     * @throws \Exception if Paymob is not configured
     */
    public function createPaymentIntention(Order $order, string $paymentMethod = 'card'): array
    {
        // Security: Ensure Paymob is configured before making API calls
        if (!$this->isConfigured()) {
            Log::error('Paymob not configured: Missing API credentials');
            return [
                'success' => false,
                'error' => 'Payment gateway not configured. Please contact support.',
            ];
        }

        $integrationId = $paymentMethod === 'wallet' 
            ? config('paymob.integrations.wallet')
            : config('paymob.integrations.card');

        // Security: Validate integration ID exists
        if (empty($integrationId)) {
            Log::error('Paymob integration ID not configured', ['method' => $paymentMethod]);
            return [
                'success' => false,
                'error' => 'Payment method not available. Please try another method.',
            ];
        }

        // Security: Sanitize user data before sending to API
        $items = $order->items->map(function ($item) {
            return [
                'name' => Str::limit(strip_tags($item->product_name), 100),
                'amount' => (int) round($item->unit_price * 100), // Amount in cents
                'quantity' => (int) $item->quantity,
            ];
        })->toArray();

        $payload = [
            'amount' => (int) round($order->total_amount * 100), // Amount in cents
            'currency' => config('paymob.currency', 'EGP'),
            'payment_methods' => [$integrationId],
            'items' => $items,
            'billing_data' => [
                'first_name' => Str::limit(strip_tags($order->first_name), 50),
                'last_name' => Str::limit(strip_tags($order->last_name), 50),
                'email' => filter_var($order->email, FILTER_SANITIZE_EMAIL),
                'phone_number' => preg_replace('/[^0-9+]/', '', $order->phone),
                'street' => Str::limit(strip_tags($order->address), 100),
                'city' => Str::limit(strip_tags($order->city), 50),
                'country' => 'EG',
                'state' => 'NA',
                'apartment' => 'NA',
                'floor' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
            ],
            'customer' => [
                'first_name' => Str::limit(strip_tags($order->first_name), 50),
                'last_name' => Str::limit(strip_tags($order->last_name), 50),
                'email' => filter_var($order->email, FILTER_SANITIZE_EMAIL),
            ],
            'extras' => [
                'order_id' => (int) $order->id,
                'order_number' => $order->order_number,
            ],
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Token ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . config('paymob.endpoints.intention'), $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Security: Validate response contains required fields
                if (empty($data['client_secret'])) {
                    Log::error('Paymob response missing client_secret', ['response' => $data]);
                    return [
                        'success' => false,
                        'error' => 'Invalid payment gateway response',
                    ];
                }
                
                return [
                    'success' => true,
                    'client_secret' => $data['client_secret'],
                    'payment_keys' => $data['payment_keys'] ?? [],
                    'intention_id' => $data['id'] ?? null,
                ];
            }

            Log::error('Paymob intention creation failed', [
                'response' => $response->json(),
                'status' => $response->status(),
                'order_id' => $order->id,
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Payment initialization failed',
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Paymob connection timeout', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Payment service temporarily unavailable. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Paymob exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Payment service unavailable',
            ];
        }
    }

    /**
     * Get the checkout URL for redirect
     */
    public function getCheckoutUrl(string $clientSecret): string
    {
        // Security: Ensure public key is configured
        $publicKey = config('paymob.public_key');
        if (empty($publicKey)) {
            Log::error('Paymob public key not configured');
        }
        
        return "https://accept.paymob.com/unifiedcheckout/?publicKey=" . urlencode($publicKey) . "&clientSecret=" . urlencode($clientSecret);
    }

    /**
     * Verify webhook HMAC signature
     * 
     * Security: Uses constant-time comparison to prevent timing attacks
     */
    public function verifyHmac(array $data, string $receivedHmac): bool
    {
        // Security: Ensure HMAC secret is configured
        if (empty($this->hmacSecret)) {
            Log::error('Paymob HMAC secret not configured');
            return false;
        }

        // Security: Build concatenated string in exact Paymob-specified order
        // TODO: Verify this concatenation order matches Paymob's latest documentation
        $parts = [
            $data['amount_cents'] ?? '',
            $data['created_at'] ?? '',
            $data['currency'] ?? '',
            $this->boolToString($data['error_occured'] ?? false),
            $this->boolToString($data['has_parent_transaction'] ?? false),
            $data['id'] ?? '',
            $data['integration_id'] ?? '',
            $this->boolToString($data['is_3d_secure'] ?? false),
            $this->boolToString($data['is_auth'] ?? false),
            $this->boolToString($data['is_capture'] ?? false),
            $this->boolToString($data['is_refunded'] ?? false),
            $this->boolToString($data['is_standalone_payment'] ?? true),
            $this->boolToString($data['is_voided'] ?? false),
            $data['order']['id'] ?? '',
            $data['owner'] ?? '',
            $this->boolToString($data['pending'] ?? false),
            $data['source_data']['pan'] ?? '',
            $data['source_data']['sub_type'] ?? '',
            $data['source_data']['type'] ?? '',
            $this->boolToString($data['success'] ?? false),
        ];

        $concatenatedString = implode('', $parts);
        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);

        // Security: Use constant-time comparison to prevent timing attacks
        return hash_equals($calculatedHmac, $receivedHmac);
    }

    /**
     * Convert boolean to string for HMAC calculation
     */
    private function boolToString($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        return strtolower((string) $value);
    }

    /**
     * Verify that the payment amount matches the order amount
     * 
     * Security: Prevents amount manipulation attacks
     */
    public function verifyPaymentAmount(Order $order, int $amountCents): bool
    {
        $expectedAmountCents = (int) round($order->total_amount * 100);
        // Allow 1 cent tolerance for rounding differences
        return abs($expectedAmountCents - $amountCents) <= 1;
    }

    /**
     * Process payment callback
     */
    public function processCallback(array $data): array
    {
        $success = $data['success'] ?? false;
        $orderId = $data['order']['merchant_order_id'] ?? null;
        $transactionId = $data['id'] ?? null;

        return [
            'success' => (bool) $success,
            'order_id' => $orderId,
            'transaction_id' => $transactionId,
            'amount_cents' => (int) ($data['amount_cents'] ?? 0),
            'amount' => ((int) ($data['amount_cents'] ?? 0)) / 100,
            'currency' => $data['currency'] ?? 'EGP',
        ];
    }

    /**
     * Get available payment methods based on database settings (with config fallback)
     */
    public function getPaymentMethods(): array
    {
        $methods = [];

        // COD - always available unless disabled
        if ($this->isPaymentMethodEnabled('cod')) {
            $methods[] = [
                'id' => 'cod',
                'name' => 'Cash on Delivery',
                'icon' => 'cash',
                'description' => 'Pay when your order is delivered',
            ];
        }

        // Card payments require Paymob configuration
        if ($this->isPaymentMethodEnabled('card') && $this->isConfigured() && config('paymob.integrations.card')) {
            $methods[] = [
                'id' => 'card',
                'name' => 'Credit / Debit Card',
                'icon' => 'card',
                'description' => 'Visa, Mastercard, Meeza',
            ];
        }

        // Mobile wallet requires Paymob configuration
        if ($this->isPaymentMethodEnabled('wallet') && $this->isConfigured() && config('paymob.integrations.wallet')) {
            $methods[] = [
                'id' => 'wallet',
                'name' => 'Mobile Wallet',
                'icon' => 'wallet',
                'description' => 'Vodafone Cash, Orange Money, Etisalat Cash',
            ];
        }

        // Fawry reference code - requires FawryPay credentials
        $fawryConfigured = !empty(config('fawry.merchant_code')) && !empty(config('fawry.secure_key'));
        if ($this->isPaymentMethodEnabled('fawry') && $fawryConfigured) {
            $methods[] = [
                'id' => 'fawry',
                'name' => 'Fawry Reference Code',
                'icon' => 'fawry',
                'description' => 'Pay at any Fawry outlet or via Fawry app',
            ];
        }

        return $methods;
    }

    /**
     * Check if a payment method is enabled
     * Uses database setting first, then falls back to config
     */
    public function isPaymentMethodEnabled(string $method): bool
    {
        // Check database setting first
        $dbSetting = \App\Models\Setting::getValue("payment_{$method}_enabled");
        
        if ($dbSetting !== null) {
            return filter_var($dbSetting, FILTER_VALIDATE_BOOLEAN);
        }
        
        // Fall back to config
        return config("paymob.methods.{$method}", true);
    }

    /**
     * Get all payment method statuses for admin panel
     */
    public function getPaymentMethodStatuses(): array
    {
        return [
            'cod' => [
                'enabled' => $this->isPaymentMethodEnabled('cod'),
                'name' => 'Cash on Delivery',
                'icon' => 'cash',
                'configured' => true,
            ],
            'card' => [
                'enabled' => $this->isPaymentMethodEnabled('card'),
                'name' => 'Credit / Debit Card',
                'icon' => 'card',
                'configured' => $this->isConfigured() && !empty(config('paymob.integrations.card')),
            ],
            'wallet' => [
                'enabled' => $this->isPaymentMethodEnabled('wallet'),
                'name' => 'Mobile Wallet',
                'icon' => 'wallet',
                'configured' => $this->isConfigured() && !empty(config('paymob.integrations.wallet')),
            ],
            'fawry' => [
                'enabled' => $this->isPaymentMethodEnabled('fawry'),
                'name' => 'Fawry Reference Code',
                'icon' => 'fawry',
                'configured' => !empty(config('fawry.merchant_code')) && !empty(config('fawry.secure_key')),
            ],
        ];
    }
    /**
     * Get transaction details from Paymob
     */
    public function getTransaction(string $transactionId): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Not configured'];
        }

        try {
            // Get Auth Token first (if not cached/stored, we get a new one)
            // Ideally we should cache this, but for now we'll just request it or use the secret if allowed.
            // Paymob V1 Authenticated API requires a token.
            // Let's try to use the secret key directly if supported by the endpoint or get a token.
            // Standard Paymob auth flow: POST /api/auth/tokens with api_key
            
            $tokenResponse = Http::post($this->baseUrl . 'api/auth/tokens', [
                'api_key' => $this->apiKey
            ]);
            
            if (!$tokenResponse->successful()) {
                Log::error('Paymob auth failed', ['response' => $tokenResponse->json()]);
                return ['success' => false, 'error' => 'Auth failed'];
            }
            
            $token = $tokenResponse->json()['token'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token, // Or just Bearer token
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . "api/acceptance/transactions/{$transactionId}");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'Transaction not found'];

        } catch (\Exception $e) {
            Log::error('Paymob getTransaction error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

