# Egypt Online Payment Integration Report

## Overview

This document provides a comprehensive guide to integrating online payment methods for e-commerce in Egypt. It covers available payment gateways, their supported methods, Laravel integration approaches, and recommendations for the Moon Fragrances project.

---

## Payment Landscape in Egypt

| Method | Market Share | Notes |
|--------|--------------|-------|
| **Cash on Delivery (COD)** | ~60-70% | Still dominant, high trust |
| **Credit/Debit Cards** | ~20-25% | Visa, Mastercard, Meeza |
| **Mobile Wallets** | ~10-15% | Growing rapidly |
| **BNPL (Buy Now Pay Later)** | ~5% | Fast-growing segment |

---

## Recommended Payment Gateways

### 1. Paymob (Primary Recommendation)

**Website**: [paymob.com](https://www.paymob.com)

**Why Paymob?**
- Largest payment gateway in Egypt
- Single integration for multiple payment methods
- PCI DSS compliant
- Excellent documentation and API
- Laravel packages available

**Supported Payment Methods:**
| Method | Type | Notes |
|--------|------|-------|
| Visa / Mastercard | Cards | International + local |
| Meeza | Cards | Egypt's national debit card |
| Vodafone Cash | Mobile Wallet | Most popular wallet |
| Orange Money | Mobile Wallet | |
| Etisalat Cash | Mobile Wallet | |
| ValU | BNPL | 6-36 month installments |
| Sympl | BNPL | Alternative BNPL |
| Bank Installments | BNPL | Via partner banks |

**Fees**: 
- Cards: 2.5% - 2.75% per transaction
- Mobile Wallets: 1% - 1.5%
- BNPL: Varies by provider

**Laravel Integration:**
```bash
composer require baselrabia/paymob-with-laravel
```

**Environment Variables (.env):**
```env
PAYMOB_API_KEY=your_api_key
PAYMOB_INTEGRATION_ID=your_integration_id
PAYMOB_IFRAME_ID=your_iframe_id
PAYMOB_HMAC_SECRET=your_hmac_secret
```

**Integration Steps:**
1. Create Paymob merchant account
2. Get API credentials from dashboard
3. Create payment intention via API
4. Redirect to hosted checkout or embed iframe
5. Handle callback/webhook for payment confirmation

---

### 2. Fawry

**Website**: [fawry.com](https://www.fawry.com)

**Why Fawry?**
- 250,000+ retail locations across Egypt
- Customers can pay with cash at kiosks
- Reference code payment (no card required)
- High trust among Egyptian consumers

**Supported Payment Methods:**
| Method | Type | Notes |
|--------|------|-------|
| Fawry Reference Code | Cash | Pay at any Fawry outlet |
| Cards | Online | Visa, Mastercard |
| Mobile Wallets | Online | |
| Fawry Pay | Wallet | Fawry's own wallet |
| BNPL | Installments | Bank installments |

**Fees**: 
- Reference code: ~2% per transaction
- Cards: 2.5% - 3%

**Laravel Integration:**
```bash
composer require laravel-pay/fawry
# OR
composer require david-maximous/fawrypay
```

**Environment Variables (.env):**
```env
FAWRY_MERCHANT_CODE=your_merchant_code
FAWRY_SECURITY_KEY=your_security_key
FAWRY_URL=https://atfawry.com/ECommerceWeb/Fawry/payments/charge
FAWRY_SANDBOX=true
```

**Integration Steps:**
1. Apply for Fawry Accept merchant account
2. Get sandbox credentials for testing
3. Implement charge request API
4. Display reference code to customer
5. Listen for payment confirmation webhook

---

### 3. Mobile Wallets (Direct Integration)

#### Vodafone Cash
- Most popular mobile wallet in Egypt (~15M users)
- Can be integrated via Paymob or directly
- Customers pay using their phone number

#### InstaPay
- Central Bank of Egypt's instant payment network
- Bank-to-wallet transfers
- Can receive payments via IPA (Instant Payment Address)

#### Orange Money / Etisalat Cash
- Similar to Vodafone Cash
- Integrated via payment gateways

> **Recommendation**: Use Paymob to handle all mobile wallets with a single integration.

---

### 4. Buy Now Pay Later (BNPL)

#### ValU
**Website**: [valu.com.eg](https://www.valu.com.eg)

- Leading BNPL provider in Egypt
- 6-60 month installment plans
- No down payment required
- Fast digital approval at checkout
- Merchant receives full payment upfront

**Integration**: Via Paymob integration or direct ValU API

#### Sympl
- Younger BNPL provider
- 3-6 month terms
- Lower order value focus

#### Bank Installments
- Available through most Egyptian banks
- 6-24 month terms
- Requires credit card

---

## Implementation Recommendation for Moon Project

### Phase 1: Quick Wins (Week 1)
1. **Keep COD** - Currently implemented ✓
2. **Add Paymob** - Single integration for:
   - Credit/Debit Cards (Visa, Mastercard, Meeza)
   - Vodafone Cash
   - Mobile wallets

### Phase 2: Extended Options (Week 2)
3. **Add Fawry Reference Code** - For customers without cards
4. **Add ValU BNPL** - For higher-value orders

### Phase 3: Advanced (Month 2)
5. **Add Bank Installments** - Via Paymob
6. **Add Orange/Etisalat wallets** - Via Paymob

---

## Database Schema Updates

```php
// Migration: add payment fields to orders table
Schema::table('orders', function (Blueprint $table) {
    $table->string('payment_method')->default('cod'); // cod, card, wallet, fawry, bnpl
    $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
    $table->string('transaction_id')->nullable();
    $table->string('payment_gateway')->nullable(); // paymob, fawry, valu
    $table->json('payment_meta')->nullable(); // Store gateway-specific data
});
```

---

## Checkout Flow Updates

### Option 1: Hosted Checkout (Recommended)
1. Customer selects payment method
2. Create payment intention via gateway API
3. Redirect to gateway's hosted checkout page
4. Gateway handles card entry securely
5. Redirect back with payment result
6. Verify via webhook

### Option 2: Embedded Checkout (iframe)
1. Customer selects payment method
2. Create payment intention via gateway API
3. Display gateway iframe in modal
4. Handle postMessage for payment result
5. Verify via webhook

---

## Security Considerations

1. **Never handle raw card data** - Use hosted checkout/iframes
2. **Verify webhooks** - Check HMAC signatures
3. **HTTPS required** - All payment pages must be SSL
4. **Store minimal data** - Only transaction IDs, not card numbers
5. **PCI Compliance** - Using hosted checkout reduces PCI scope

---

## Required Documents for Merchant Accounts

### Paymob
- Commercial registration
- Tax card
- National ID of authorized signatory
- Bank account details
- Website/app information

### Fawry
- Commercial registration
- Tax card
- National ID
- Company profile
- Expected transaction volume

### ValU
- Commercial registration
- Partnership agreement
- Integration verification

---

## Recommended Next Steps

1. [ ] Create Paymob sandbox account
2. [ ] Install Paymob Laravel package
3. [ ] Create PaymentService class
4. [ ] Update checkout UI with payment method selection
5. [ ] Implement payment callback/webhook
6. [ ] Add order payment status tracking
7. [ ] Test with sandbox transactions
8. [ ] Apply for production credentials
9. [ ] Go live with card payments
10. [ ] Add Fawry reference code option

---

## Useful Links

| Resource | URL |
|----------|-----|
| Paymob Docs | https://docs.paymob.com |
| Paymob Dashboard | https://accept.paymob.com |
| Fawry Staging | https://fawrystaging.com |
| Fawry Docs | https://developer.fawrystaging.com |
| ValU Merchant | https://merchant.valu.com.eg |
| Laravel Paymob | https://github.com/baselrabia/paymob-with-laravel |
| Laravel Fawry | https://github.com/david-maximous/fawrypay |

---

## Summary

For the Moon Fragrances project, I recommend:

1. **Primary Gateway**: Paymob (cards + wallets + BNPL)
2. **Secondary Gateway**: Fawry (reference code for cash preference)
3. **Keep COD**: Important for customer trust in Egypt

This combination will cover ~95% of Egyptian e-commerce payment preferences.
