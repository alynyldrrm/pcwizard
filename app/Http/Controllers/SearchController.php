<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    // Tam arama
    public function search(Request $request)
    {
        $query = $request->get('q');
        $results = [
            'products' => [],
            'categories' => [],
            'brands' => []
        ];

        if (strlen($query) >= 2) {

            // Ürün arama
            $products = Product::where('Ad', 'LIKE', "%{$query}%")
                ->orWhere('Marka', 'LIKE', "%{$query}%")
                ->orWhere('Model', 'LIKE', "%{$query}%")
                ->limit(20)
                ->get();

            // Kategori arama
            $categories = Category::where('CategoryName', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();

            // Marka arama
            $brands = Product::where('Marka', 'LIKE', "%{$query}%")
                ->distinct()
                ->pluck('Marka')
                ->take(10)
                ->values(); // collection to array

            $results = [
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands
            ];
        }

        return response()->json($results);
    }

    // Live search (dropdown)
    public function liveSearch(Request $request)
    {
        $query = $request->get('q');
        $results = [
            'products' => [],
            'categories' => [],
            'brands' => []
        ];

        if (strlen($query) >= 2) {

            $products = Product::where('Ad', 'LIKE', "%{$query}%")
                ->orWhere('Marka', 'LIKE', "%{$query}%")
                ->orWhere('Model', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();

            $categories = Category::where('CategoryName', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get();

            $brands = Product::where('Marka', 'LIKE', "%{$query}%")
                ->distinct()
                ->pluck('Marka')
                ->take(3)
                ->values();

            $results = [
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands
            ];
        }

        return response()->json($results);
    }
}
