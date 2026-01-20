<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Collection;
use App\Models\Product;

class HomeController extends Controller
{
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
        $featured = Product::where('featured', true)->take(8)->get();
        $trending = Product::where('trending', true)->take(8)->get();

        return view('welcome', compact('hero', 'collections', 'featured', 'trending'));
    }
}
