@props(['type' => 'website', 'data' => []])

@php
    $schema = [];

    if ($type === 'product') {
        $product = $data['product'];
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->description),
            'image' => asset($product->image),
            'sku' => $product->id,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'MOON'
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => 'EGP',
                'price' => $product->price,
                'priceValidUntil' => now()->addMonth()->toIso8601String(),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $product->isOutOfStock() ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
            ]
        ];

        if ($product->reviews_count > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->average_rating,
                'reviewCount' => $product->reviews_count
            ];
        }
    } elseif ($type === 'organization') {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'MOON | Luxury Arabian Fashion',
            'url' => url('/'),
            'logo' => asset('moon-icon.svg'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+201000000000', // Update with real number if available
                'contactType' => 'customer service',
                'areaServed' => 'EG',
                'availableLanguage' => ['Arabic', 'English']
            ]
        ];
    } elseif ($type === 'website') {
         $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'MOON',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/shop?search={search_term_string}'),
                'query-input' => 'required name=search_term_string'
            ]
        ];
    } elseif ($type === 'breadcrumb') {
        $itemList = [];
        foreach ($data['breadcrumbs'] as $index => $crumb) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'] ?? null
            ];
        }
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList
        ];
    }
@endphp

<script type="application/ld+json">
    {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
