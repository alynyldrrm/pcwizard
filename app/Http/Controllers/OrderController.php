<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function showForm()
    {
        return view('siparis-sorgula'); // Blade dosyası
    }

    public function queryOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $orderData = DB::table('orders')
            ->where('OrderNumber', $request->order_number)
            ->first();

        if (!$orderData) {
            return back()->with('error', 'Sipariş bulunamadı.');
        }

        $orderItems = DB::table('order_items')
            ->where('OrderId', $orderData->OrderId)
            ->get();

        return view('siparis-sorgula', compact('orderData', 'orderItems'));
    }
}
