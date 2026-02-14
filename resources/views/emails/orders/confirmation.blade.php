<x-mail::message>
# Order Confirmed

Hi {{ $order->first_name }},

Thank you for your order! We have received it and will start processing it shortly.

**Order Number:** {{ $order->order_number }}
**Total:** EGP {{ number_format($order->total_amount, 2) }}

## Order Summary

@foreach($order->items as $item)
- **{{ $item->product_name }}** ({{ $item->size }}) x {{ $item->quantity }} - EGP {{ number_format($item->total, 2) }}
@endforeach

<x-mail::button :url="route('orders.show', $order->order_number)">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
