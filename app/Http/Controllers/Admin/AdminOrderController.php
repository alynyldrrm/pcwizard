<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // Tüm siparişleri listele
    public function index()
    {
        $orders = Order::with('user', 'items')->orderBy('OrderDate', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Sipariş detayını göster
public function show($orderId)
{
    $order = \App\Models\Order::with('user', 'items')->findOrFail($orderId);

    // TotalAmount ve item fiyatlarını float yap
    $order->TotalAmount = (float) $order->TotalAmount;

    foreach ($order->items as $item) {
        $item->UnitPrice = (float) $item->UnitPrice;
        $item->TotalPrice = (float) $item->TotalPrice;
    }

    return response()->json($order);
}

}
