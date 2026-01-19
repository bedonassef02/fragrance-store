<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $hero = [
            'image' => 'https://images.pexels.com/photos/18269632/pexels-photo-18269632/free-photo-of-woman-in-hijab-posing-on-desert.jpeg?auto=compress&cs=tinysrgb&w=2000',
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

        $collections = [
            [
                'image' => 'https://images.pexels.com/photos/15865612/pexels-photo-15865612/free-photo-of-brunette-in-abaya.jpeg?auto=compress&cs=tinysrgb&w=800',
                'title' => 'Abayas',
                'route' => 'shop',
            ],
            [
                'image' => 'https://images.pexels.com/photos/13758155/pexels-photo-13758155.jpeg?auto=compress&cs=tinysrgb&w=800',
                'title' => 'Collections',
                'route' => 'collections',
            ],
            [
                'image' => 'https://images.pexels.com/photos/1117272/pexels-photo-1117272.jpeg?auto=compress&cs=tinysrgb&w=800',
                'title' => 'Bags',
                'route' => 'shop',
            ],
        ];

        return view('welcome', compact('hero', 'collections'));
    }
}
