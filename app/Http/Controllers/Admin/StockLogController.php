<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockLog;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StockLogController extends Controller
{
    public function index()
    {
        $logs = StockLog::with(['product', 'admin'])
            ->orderBy('islem_tarihi', 'desc')
            ->get();

        return view('admin.stock_logs.index', compact('logs'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.stock_logs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:Products,ProductId',
            'miktar_change' => 'required|integer',
            'islem_tipi' => 'required|in:ekleme,çıkarma',
        ]);

        // Stok güncelle
        $stock = Stock::firstOrCreate(
            ['ProductId' => $request->product_id],
            ['Miktar' => 0]
        );

        if ($request->islem_tipi == 'ekleme') {
            $stock->Miktar += $request->miktar_change;
        } else {
            $stock->Miktar -= $request->miktar_change;
        }
        $stock->GuncellemeTarihi = now();
        $stock->save();

        // Stock log kaydı
        StockLog::create([
            'stock_id' => $stock->StockId,
            'product_id' => $request->product_id,
            'admin_id' => Auth::id(),
            'miktar_change' => $request->miktar_change,
            'islem_tipi' => $request->islem_tipi,
            'islem_tarihi' => now(),
        ]);

        return redirect()->route('admin.stock_logs.index')->with('success', 'Stok güncellendi ve log kaydedildi.');
    }
}
