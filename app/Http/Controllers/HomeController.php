<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Collection;
use App\Services\ProductService;

class HomeController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): View
    {
        $heroImage = \App\Models\Setting::getValue('home_hero_image', 'https://images.pexels.com/photos/18269632/pexels-photo-18269632/free-photo-of-woman-in-hijab-posing-on-desert.jpeg?auto=compress&cs=tinysrgb&w=2000');

        $hero = [
            'image' => asset('storage/' . $heroImage),
            'subtitle' => 'The New Collection',
            'title' => 'Elegance',
            'title_highlight' => 'Redefined',
            'description' => 'Discover the finest Arabian fashion where timeless tradition meets modern luxury.',
            'primary_cta' => [
                'text' => 'Shop Abayas',
                'route' => 'shop',
            ],
            'secondary_cta' => [
                'text' => 'Discover Bags',
                'route' => 'collections',
            ]
        ];

        $collections = Collection::orderBy('sort_order')->take(3)->get();
        $featured = $this->productService->getFeaturedProducts(8);
        $trending = $this->productService->getTrendingProducts(8);

        return view('welcome', compact('hero', 'collections', 'featured', 'trending'));
    }
}
