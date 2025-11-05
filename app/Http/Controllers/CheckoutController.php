<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        // Sepet verilerini alıp view'a gönder
        $cartItems = session('cart', []);
        $totalAmount = array_sum(array_column($cartItems, 'price'));

        return view('checkout', compact('cartItems', 'totalAmount'));
    }
}
