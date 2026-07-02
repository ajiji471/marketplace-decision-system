<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SawService;
use App\Services\WpService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpkController extends Controller
{
    private function getCriteria(): array
    {
        return [
            ['key' => 'margin_percent', 'label' => 'Margin Keuntungan (%)', 'type' => 'benefit', 'default_weight' => 0.25],
            ['key' => 'sold_count', 'label' => 'Jumlah Terjual', 'type' => 'benefit', 'default_weight' => 0.20],
            ['key' => 'rating', 'label' => 'Rating Rata-rata', 'type' => 'benefit', 'default_weight' => 0.15],
            ['key' => 'search_volume', 'label' => 'Volume Pencarian', 'type' => 'benefit', 'default_weight' => 0.15],
            ['key' => 'competitor_count', 'label' => 'Jumlah Pesaing', 'type' => 'cost', 'default_weight' => 0.10],
            ['key' => 'price_volatility', 'label' => 'Volatilitas Harga', 'type' => 'cost', 'default_weight' => 0.10],
            ['key' => 'shipping_complexity', 'label' => 'Kompleksitas Pengiriman', 'type' => 'cost', 'default_weight' => 0.05],
        ];
    }

    private function getCategories()
    {
        return Product::select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();
    }

    public function index()
    {
        return Inertia::render('Spk/Index', [
            'criteria' => $this->getCriteria(),
            'categories' => $this->getCategories(),
            'results' => null,
            'filters' => null,
        ]);
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|in:saw,wp,both',
            'category' => 'nullable|string',
            'weights' => 'nullable|array',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Product::with('marketplaceData');
        
        if (!empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        $products = $query->get();
        $limit = $validated['limit'] ?? 20;
        $response = [];

        if (in_array($validated['method'], ['saw', 'both'])) {
            $sawService = new SawService();
            $sawResults = $sawService->calculate($products, $validated['weights'] ?? null);
            $response['saw'] = array_slice($sawResults, 0, $limit);
        }

        if (in_array($validated['method'], ['wp', 'both'])) {
            $wpService = new WpService();
            $wpResults = $wpService->calculate($products, $validated['weights'] ?? null);
            $response['wp'] = array_slice($wpResults, 0, $limit);
        }

        return Inertia::render('Spk/Index', [
            'criteria' => $this->getCriteria(),
            'categories' => $this->getCategories(),
            'results' => $response,
            'filters' => [
                'method' => $validated['method'],
                'category' => $validated['category'] ?? '',
                'weights' => $validated['weights'] ?? null,
            ],
        ]);
    }
}