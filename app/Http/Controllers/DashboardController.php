<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        $stats = [
            'totalProducts' => $products->count(),
            'avgMargin' => $products->count() > 0 
                ? round($products->avg('margin_percent'), 1) . '%' 
                : '0%',
            'highMarginProducts' => $products->where('margin_percent', '>', 30)->count(),
            'totalCategories' => $products->pluck('category')->unique()->filter()->count(),
        ];
        
        return Inertia::render('Dashboard', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }
}