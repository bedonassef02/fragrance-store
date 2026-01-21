<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = url('/');

        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $content .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        // Static Pages
        $staticPages = [
            '/' => 'daily',
            '/shop' => 'daily',
            '/about' => 'monthly',
            '/collections' => 'weekly',
        ];

        foreach ($staticPages as $path => $freq) {
            $content .= "\t<url>\n";
            $content .= "\t\t<loc>" . $baseUrl . $path . "</loc>\n";
            $content .= "\t\t<lastmod>" . now()->toAtomString() . "</lastmod>\n";
            $content .= "\t\t<changefreq>" . $freq . "</changefreq>\n";
            $content .= "\t\t<priority>0.8</priority>\n";
            $content .= "\t</url>\n";
        }

        // Products
        $products = Product::latest()->get();
        foreach ($products as $product) {
            $content .= "\t<url>\n";
            $content .= "\t\t<loc>" . route('product.show', $product->slug) . "</loc>\n";
            $content .= "\t\t<lastmod>" . $product->updated_at->toAtomString() . "</lastmod>\n";
            $content .= "\t\t<changefreq>weekly</changefreq>\n";
            $content .= "\t\t<priority>1.0</priority>\n";
            $content .= "\t</url>\n";
        }

        $content .= "</urlset>";

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
