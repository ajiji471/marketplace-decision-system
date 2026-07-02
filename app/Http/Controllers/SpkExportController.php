<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SawService;
use App\Services\WpService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkExportController extends Controller
{
    public function exportPdf(Request $request)
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
        $sawResults = [];
        $wpResults = [];

        if (in_array($validated['method'], ['saw', 'both'])) {
            $sawService = new SawService();
            $sawResults = array_slice(
                $sawService->calculate($products, $validated['weights'] ?? null),
                0,
                $limit
            );
        }

        if (in_array($validated['method'], ['wp', 'both'])) {
            $wpService = new WpService();
            $wpResults = array_slice(
                $wpService->calculate($products, $validated['weights'] ?? null),
                0,
                $limit
            );
        }

        $data = [
            'method' => $validated['method'],
            'category' => $validated['category'] ?? 'Semua Kategori',
            'weights' => $validated['weights'] ?? [],
            'sawResults' => $sawResults,
            'wpResults' => $wpResults,
            'generatedAt' => now()->format('d M Y H:i'),
            'totalProducts' => $products->count(),
        ];

        $pdf = Pdf::loadView('pdf.spk-report', $data);

        $filename = 'spk-report-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}