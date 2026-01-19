<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = [
            [
                'title' => 'Ramadan 2026',
                'subtitle' => 'Special Edition',
                'image' => 'https://images.unsplash.com/photo-1628045620958-8671607590d9?q=80&w=1000',
                'route' => 'shop',
                'cta' => 'Shop the Look',
                'class' => '',
                'cta_class' => 'text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors',
            ],
            [
                'title' => 'Modern Minimalist',
                'subtitle' => 'New Season',
                'image' => 'https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800',
                'route' => 'shop',
                'cta' => 'Shop the Look',
                'class' => '',
                'cta_class' => 'text-white border-b border-white pb-1 hover:text-moon-gold hover:border-moon-gold transition-colors',
            ],
            [
                'title' => 'The Essentials Edit',
                'subtitle' => 'Everyday Luxury',
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=1600',
                'route' => 'shop',
                'cta' => 'Explore All',
                'class' => 'md:col-span-2',
                'cta_class' => 'inline-block bg-white text-black px-8 py-3 uppercase tracking-widest text-xs font-bold hover:bg-moon-gold hover:text-white transition-colors',
            ],
        ];

        return view('collections.index', compact('collections'));
    }
}
