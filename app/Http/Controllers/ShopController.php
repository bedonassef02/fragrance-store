<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->getProducts();
        return view('shop.index', compact('products'));
    }

    /**
     * Mock Data Service
     */
    public static function getProducts()
    {
        return [
            [
                'id' => 1,
                'name' => 'Royal Black Abaya',
                'description' => 'Exquisitely crafted from premium nida fabric, this Royal Black Abaya features intricate gold embroidery along the cuffs and hem.',
                'price' => 2800,
                'original_price' => 3500,
                'image' => 'https://images.pexels.com/photos/9940866/pexels-photo-9940866.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => '-20%',
                'badge_color' => 'bg-red-600',
                'category' => 'Abayas'
            ],
            [
                'id' => 2,
                'name' => 'Crimson Velvet Kaftan',
                'description' => 'A statement piece for evening wear, this Crimson Velvet Kaftan drapes elegantly with a luxurious sheen.',
                'price' => 4200,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/28905393/pexels-photo-28905393/free-photo-of-elegant-woman-in-red-traditional-dress-in-marrakech.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Kaftans'
            ],
            [
                'id' => 3,
                'name' => 'Embossed Leather Clutch',
                'description' => 'Detailed embossed leather clutch with gold hardware.',
                'price' => 1850,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/1152077/pexels-photo-1152077.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => 'New',
                'badge_color' => 'bg-moon-gold',
                'category' => 'Bags'
            ],
            [
                'id' => 4,
                'name' => 'Desert Rose Dress',
                'description' => 'Inspired by the hues of the desert sunset, this dress features flowing chiffon layers.',
                'price' => 3100,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/16848560/pexels-photo-16848560/free-photo-of-woman-in-dress-in-desert.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Dresses'
            ],
            [
                'id' => 5,
                'name' => 'Midnight Silk Abaya',
                'description' => 'Deep midnight blue silk abaya that shimmers under the evening light.',
                'price' => 2950,
                'original_price' => null,
                'image' => 'https://images.pexels.com/photos/20344409/pexels-photo-20344409/free-photo-of-woman-in-long-coat-posing-in-passage.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => null,
                'badge_color' => null,
                'category' => 'Abayas'
            ],
            [
                'id' => 6,
                'name' => 'Gold Chain Satchel',
                'description' => 'Compact yet spacious satchel with a signature gold chain strap.',
                'price' => 1870,
                'original_price' => 2200,
                'image' => 'https://images.pexels.com/photos/298863/pexels-photo-298863.jpeg?auto=compress&cs=tinysrgb&w=800',
                'badge' => '-15%',
                'badge_color' => 'bg-red-600',
                'category' => 'Bags'
            ]
        ];
    }
}
