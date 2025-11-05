<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Stock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Campaign; // ✅ Burada olacak
use App\Models\CampaignCategory;
use App\Models\CampaignProduct;
use App\Models\CampaignSubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UrunController extends Controller

{
    public function index(Request $request)
    {
        // Filtreleme (bu kısım zaten vardı, harika!)
        $query = Product::query();

        if($request->category){
            $query->where('CategoryId', $request->category);
        }

        if($request->brand){
            $query->where('Marka', 'like', '%'.$request->brand.'%');
        }

        if($request->min_price){
            $query->where('Fiyat', '>=', $request->min_price);
        }

        if($request->max_price){
            $query->where('Fiyat', '<=', $request->max_price);
        }

        if($request->search){
            $query->where('Ad', 'like', '%'.$request->search.'%');
        }

        $products = $query->paginate(12); // sayfalama

        
        $categories = Category::all(); // Blade'de kullanmak için

        // Ürünleri 'welcome' sayfasına gönderiyoruz
        return view('welcome', compact('products', 'categories'));
    }

    /**
     * URL'den gelen kategori ID'sine göre ürünleri listeler.
     *
     * @param int $categoryId
     * @return \Illuminate\View\View
     */
    public function kategoriyeGoreUrunleriGoster($categoryId)
    {
        // Ürünleri kategori ID'sine göre filtrele
        $products = Product::where('CategoryId', $categoryId)->paginate(12);

        // Menü için tüm kategorileri çek
        $categories = Category::with('subcategories')->get();

        return view('welcome', compact('products', 'categories'));
    }

    public function altKategoriyeGoreUrunleriGoster($subCategoryId)
    {
        // Ürünleri alt kategori ID'sine göre filtrele
        $products = Product::where('SubCategoryId', $subCategoryId)->paginate(12);

        // Menü için tüm kategorileri çek
        $categories = Category::with('subcategories')->get();
        
        return view('welcome', compact('products', 'categories'));
    }

  
    
    public function showDetails($id)
    {
        $product = Product::with('criterias.kriter')->findOrFail($id);
        
        return response()->json($product);
    }

    public function urunlerSayfasi(Request $request)
    {
        $query = Product::query();

        if($request->category){
            $query->where('CategoryId', $request->category);
        }

        if($request->subcategory){
            $query->where('SubCategoryId', $request->subcategory);
        }

        if($request->brand){
            $query->where('Marka', $request->brand);
        }

        if($request->min_price){
            $query->where('Fiyat', '>=', $request->min_price);
        }

        if($request->max_price){
            $query->where('Fiyat', '<=', $request->max_price);
        }

        if($request->search){
            $query->where('Ad', 'like', '%'.$request->search.'%');
        }

        $products = $query->paginate(12);

        // Kategoriler
        $categories = Category::with('subcategories')->get();

        // Markalar (distinct)
        $brands = Product::select('Marka')->distinct()->pluck('Marka');

        return view('urunler', compact('products', 'categories', 'brands'));
    }
    
    public function getSubcategories($categoryId)
    {
        $category = Category::with('subcategories')->find($categoryId);

        if (!$category) {
            return response()->json([]);
        }

        return response()->json($category->subcategories);
    }

    // Sepete ekleme fonksiyonu (hem giriş yapmış hem yapmamış kullanıcılar için)
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) return response()->json(['error' => 'Ürün bulunamadı'], 404);

        // Stok kontrolü
        $stock = Stock::where('ProductId', $product->ProductId)->first();
        if (!$stock || $stock->Miktar < 1) {
            return response()->json(['error' => 'Üzgünüz, bu üründen stokta kalmadı.'], 400);
        }

        if (Auth::check()) {
            // Giriş yapmış kullanıcı - veritabanına kaydet
            $cartItem = CartItem::firstOrNew([
                'UserId' => Auth::id(),
                'ProductId' => $product->ProductId
            ]);

            $newQuantity = ($cartItem->Quantity ?? 0) + ($request->quantity ?? 1);
            if ($newQuantity > $stock->Miktar) {
                $cartItem->Quantity = $stock->Miktar;
                $message = "Ürün stoğu sadece {$stock->Miktar}. Sepetinize mevcut stoğa göre eklendi.";
            } else {
                $cartItem->Quantity = $newQuantity;
                $message = "Ürün sepete eklendi.";
            }

            $cartItem->UnitPrice = $product->Fiyat;
            $cartItem->TotalPrice = $cartItem->Quantity * $cartItem->UnitPrice;
            $cartItem->ProductName = $product->Ad;
            $cartItem->ProductImage = $product->Resim;
            $cartItem->ProductBrand = $product->Marka;
            $cartItem->ProductModel = $product->Model;
            $cartItem->CategoryId = $product->CategoryId;
            $cartItem->save();

            $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');
        } else {
            // Giriş yapmamış kullanıcı - session'a kaydet
            $sessionCart = session('guest_cart', []);
            $quantity = $request->quantity ?? 1;
            
            if (isset($sessionCart[$product->ProductId])) {
                $sessionCart[$product->ProductId]['quantity'] += $quantity;
                if ($sessionCart[$product->ProductId]['quantity'] > $stock->Miktar) {
                    $sessionCart[$product->ProductId]['quantity'] = $stock->Miktar;
                    $message = "Ürün stoğu sadece {$stock->Miktar}. Sepetinize mevcut stoğa göre eklendi.";
                } else {
                    $message = "Ürün sepete eklendi.";
                }
            } else {
                $sessionCart[$product->ProductId] = [
                    'product_id' => $product->ProductId,
                    'quantity' => $quantity > $stock->Miktar ? $stock->Miktar : $quantity,
                    'name' => $product->Ad,
                    'price' => $product->Fiyat,
                    'image' => $product->Resim,
                    'brand' => $product->Marka,
                    'model' => $product->Model,
                    'category_id' => $product->CategoryId
                ];
                $message = $quantity > $stock->Miktar 
                    ? "Ürün stoğu sadece {$stock->Miktar}. Sepetinize mevcut stoğa göre eklendi."
                    : "Ürün sepete eklendi.";
            }

            session(['guest_cart' => $sessionCart]);
            $cartCount = array_sum(array_column($sessionCart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $cartCount,
            'product' => [
                'name' => $product->Ad,
                'brand' => $product->Marka,
                'model' => $product->Model,
                'price' => number_format($product->Fiyat, 2, ',', '.')
            ]
        ]);
    }

    // Sepet içeriğini getir
    public function getCart()
    {
        if (Auth::check()) {
            $cartItems = CartItem::where('UserId', Auth::id())->get();
            $totalAmount = $cartItems->sum('TotalPrice');
            $cartCount = $cartItems->sum('Quantity');
            
            return response()->json([
                'cart_items' => $cartItems,
                'total_amount' => $totalAmount,
                'cart_count' => $cartCount
            ]);
        } else {
            $sessionCart = session('guest_cart', []);
            $cartItems = [];
            $totalAmount = 0;
            $cartCount = 0;
            
            foreach ($sessionCart as $item) {
                $cartItems[] = (object)[
                    'ProductId' => $item['product_id'],
                    'ProductName' => $item['name'],
                    'ProductBrand' => $item['brand'],
                    'ProductModel' => $item['model'],
                    'ProductImage' => $item['image'],
                    'Quantity' => $item['quantity'],
                    'UnitPrice' => $item['price'],
                    'TotalPrice' => $item['quantity'] * $item['price'],
                    'CartItemId' => 'guest_' . $item['product_id'] // Guest için geçici ID
                ];
                $totalAmount += $item['quantity'] * $item['price'];
                $cartCount += $item['quantity'];
            }
            
            return response()->json([
                'cart_items' => $cartItems,
                'total_amount' => $totalAmount,
                'cart_count' => $cartCount
            ]);
        }
    }

    // Sepetten ürün kaldır
    public function removeFromCart(Request $request)
    {
        if (Auth::check()) {
            $cartItemId = $request->input('cart_item_id');
            
            $cartItem = CartItem::where('CartItemId', $cartItemId)
                               ->where('UserId', Auth::id())
                               ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sepet öğesi bulunamadı'], 404);
            }

            $cartItem->delete();
            $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün sepetten kaldırıldı',
                'cart_count' => $cartCount
            ]);
        } else {
            $cartItemId = $request->input('cart_item_id');
            $productId = str_replace('guest_', '', $cartItemId);
            
            $sessionCart = session('guest_cart', []);
            if (isset($sessionCart[$productId])) {
                unset($sessionCart[$productId]);
                session(['guest_cart' => $sessionCart]);
                
                $cartCount = array_sum(array_column($sessionCart, 'quantity'));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Ürün sepetten kaldırıldı',
                    'cart_count' => $cartCount
                ]);
            }
            
            return response()->json(['error' => 'Sepet öğesi bulunamadı'], 404);
        }
    }

    // Session cart'ı veritabanına aktar (giriş yaptığında)
    public function mergeSessionCart()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmalısınız'], 401);
        }

        $sessionCart = session('guest_cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $cartItem = CartItem::firstOrNew([
                        'UserId' => Auth::id(),
                        'ProductId' => $product->ProductId
                    ]);

                    $cartItem->Quantity = ($cartItem->Quantity ?? 0) + $item['quantity'];
                    $cartItem->UnitPrice = $product->Fiyat;
                    $cartItem->TotalPrice = $cartItem->Quantity * $cartItem->UnitPrice;
                    $cartItem->ProductName = $product->Ad;
                    $cartItem->ProductImage = $product->Resim;
                    $cartItem->ProductBrand = $product->Marka;
                    $cartItem->ProductModel = $product->Model;
                    $cartItem->CategoryId = $product->CategoryId;
                    $cartItem->save();
                }
            }
            
            // Session cart'ı temizle
            session()->forget('guest_cart');
        }

        $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');
        
        return response()->json([
            'success' => true,
            'message' => 'Sepet birleştirildi',
            'cart_count' => $cartCount
        ]);
    }

    // Misafir kullanıcı için sipariş oluştur
    public function createGuestOrder(Request $request)
    {
        $sessionCart = session('guest_cart', []);
        
        if (empty($sessionCart)) {
            return response()->json(['error' => 'Sepetiniz boş!'], 400);
        }

        // Misafir bilgilerini doğrula
        $request->validate([
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email|max:100',
            'guest_phone' => 'required|string|max:20',
            'guest_address' => 'required|string|max:255',
            'guest_city' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Stok kontrolü
            foreach ($sessionCart as $item) {
                $stock = Stock::where('ProductId', $item['product_id'])->first();
                if (!$stock || $stock->Miktar < $item['quantity']) {
                    return response()->json([
                        'error' => "Üzgünüz, '{$item['name']}' ürününden yeterli stok yok. Mevcut stok: " . ($stock->Miktar ?? 0)
                    ], 400);
                }
            }

            // Toplam tutarı hesapla
            $totalAmount = array_sum(array_map(function($item) {
                return $item['quantity'] * $item['price'];
            }, $sessionCart));

            // Misafir siparişi oluştur
            $order = Order::create([
                'UserId' => null, // Misafir siparişi
                'OrderNumber' => 'GUEST-' . time() . rand(1000, 9999),
                'OrderStatus' => 'Pending',
                'TotalAmount' => $totalAmount,
                'OrderDate' => now(),
                'GuestName' => $request->guest_name,
                'GuestEmail' => $request->guest_email,
                'GuestPhone' => $request->guest_phone,
                'GuestAddress' => $request->guest_address,
                'GuestCity' => $request->guest_city,
            ]);

            // Sipariş öğelerini ekle
            foreach ($sessionCart as $item) {
                $product = Product::find($item['product_id']);
                OrderItem::create([
                    'OrderId' => $order->OrderId,
                    'ProductId' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'UnitPrice' => $item['price'],
                    'TotalPrice' => $item['quantity'] * $item['price'],
                    'ProductName' => $item['name'],
                    'ProductImage' => $item['image'],
                    'ProductBrand' => $item['brand'],
                    'ProductModel' => $item['model'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->OrderId,
                'redirect_url' => route('guest.payment', ['orderId' => $order->OrderId])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Sipariş oluşturulamadı: ' . $e->getMessage()], 500);
        }
    }

    // Misafir ödeme sayfası
    public function showGuestPaymentPage($orderId)
    {
        $order = Order::where('OrderId', $orderId)
                     ->whereNull('UserId') // Misafir siparişi kontrolü
                     ->with('items')
                     ->firstOrFail();

        return view('guest.payment', compact('order'));
    }

public function processGuestPayment(Request $request)
{
    $validated = $request->validate([
        'order_id' => 'required|exists:orders,OrderId',
        'card_name' => 'required|string|max:255',
        'card_number' => 'required|string|max:19',
        'expiry_month' => 'required|integer|min:1|max:12',
        'expiry_year' => 'required|integer|min:2024|max:2034',
        'cvv' => 'required|digits:3',
        'billing_address' => 'required|string|max:500',
        'billing_city' => 'required|string|max:255',
        'billing_phone' => 'required|string|max:20',
    ]);

    $order = Order::find($validated['order_id']);
    if (!$order) {
        return response()->json(['error' => 'Sipariş bulunamadı'], 404);
    }

    $paymentSuccessful = $this->simulatePayment($validated, $order->TotalAmount);

    if (!$paymentSuccessful) {
        return response()->json([
            'success' => false,
            'message' => 'Ödeme başarısız!'
        ], 400);
    }

    $order->OrderStatus = 'Paid';
    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Ödeme başarılı!',
        'redirect_url' => route('guest.order.success', ['orderId' => $order->OrderId])
    ]);
}

public function guestOrderSuccess($orderId)
{
    $order = Order::where('OrderId', $orderId)
                  ->whereNull('UserId') // Misafir siparişi
                  ->with('items')
                  ->firstOrFail();

    return view('guest.order-success', compact('order'));
}



    // Ödeme simülasyonu (WizardController'dan kopyalandı)
    private function simulatePayment($paymentData, $amount)
    {
        $cardNumber = str_replace(' ', '', $paymentData['card_number']);
        
        // Test kartları (başarılı ödeme)
        $successfulCards = ['4111111111111111', '5555555555554444', '4000000000000002'];
        
        if (in_array($cardNumber, $successfulCards)) {
            sleep(2); // Ödeme işlemi simülasyonu
            return true;
        }
        
        // Başarısız kart
        if ($cardNumber === '4000000000000069') {
            return false;
        }
        
        // Diğer kartlar için random başarı
        return rand(1, 10) > 2; // %80 başarı oranı
    }

    
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'subCategory', 'criterias.kriter'])
                ->findOrFail($id);
            
            // Benzer ürünler (aynı kategori veya marka)
            $similarProducts = Product::where('ProductId', '!=', $id)
                ->where(function($query) use ($product) {
                    $query->where('CategoryId', $product->CategoryId)
                          ->orWhere('Marka', $product->Marka);
                })
                ->limit(8)
                ->get();

            return view('product-detail', compact('product', 'similarProducts'));
            
        } catch (\Exception $e) {
            return redirect()->route('urunler')->with('error', 'Ürün bulunamadı.');
        }
    }

    /**
     * Modal için AJAX ürün detayları
     */
    public function getDetails($id)
    {
        try {
            $product = Product::with(['category', 'subCategory', 'criterias.kriter'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => [
                    'ProductId' => $product->ProductId,
                    'Ad' => $product->Ad,
                    'Fiyat' => $product->Fiyat,
                    'Marka' => $product->Marka,
                    'Model' => $product->Model,
                    'Resim' => $product->Resim,
                    'Aciklama' => $product->Aciklama,
                    'Ozellikler' => $product->Ozellikler,
                    'category' => $product->category ? [
                        'CategoryId' => $product->category->CategoryId,
                        'CategoryName' => $product->category->CategoryName
                    ] : null,
                    'subCategory' => $product->subCategory ? [
                        'SubCategoryId' => $product->subCategory->SubCategoryId,
                        'SubCategoryName' => $product->subCategory->SubCategoryName
                    ] : null,
                    'criterias' => $product->criterias->map(function($criteria) {
                        return [
                            'CriteriaId' => $criteria->CriteriaId,
                            'CriteriaValue' => $criteria->CriteriaValue,
                            'kriter' => $criteria->kriter ? [
                                'KriterId' => $criteria->kriter->KriterId,
                                'KriterAdi' => $criteria->kriter->KriterAdi
                            ] : null
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Ürün detayları yüklenemedi.'
            ], 404);
        }
    }

    /**
     * Wishlist toggle (favorilere ekle/çıkar)
     */
    public function toggleWishlist(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Favorilere eklemek için giriş yapmalısınız.'
            ], 401);
        }

        try {
            $productId = $request->input('product_id');
            $userId = auth()->id();

            // Wishlist tablosunu kontrol et (bu tabloyu oluşturmanız gerekecek)
            $existing = DB::table('wishlists')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                // Favorilerden çıkar
                DB::table('wishlists')
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Ürün favorilerden kaldırıldı.'
                ]);
            } else {
                // Favorilere ekle
                DB::table('wishlists')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'created_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Ürün favorilere eklendi.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'İşlem gerçekleştirilemedi.'
            ], 500);
        }
    }

    public function showCheckout()
{
    // Sepet kontrolü
    if (Auth::check()) {
        $cartItems = CartItem::where('UserId', Auth::id())->get();
        $totalAmount = $cartItems->sum('TotalPrice');
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('urunler')->with('error', 'Sepetiniz boş!');
        }
    } else {
        $sessionCart = session('guest_cart', []);
        if (empty($sessionCart)) {
            return redirect()->route('urunler')->with('error', 'Sepetiniz boş!');
        }
        
        $cartItems = [];
        $totalAmount = 0;
        foreach ($sessionCart as $item) {
            $cartItems[] = (object)[
                'ProductId' => $item['product_id'],
                'ProductName' => $item['name'],
                'ProductBrand' => $item['brand'],
                'ProductModel' => $item['model'],
                'ProductImage' => $item['image'],
                'Quantity' => $item['quantity'],
                'UnitPrice' => $item['price'],
                'TotalPrice' => $item['quantity'] * $item['price']
            ];
            $totalAmount += $item['quantity'] * $item['price'];
        }
    }
    
    return view('checkout', compact('cartItems', 'totalAmount'));
}


public function applyCoupon(Request $request)
{
    $couponCode = $request->input('coupon_code');

    // Sepeti al
    $cartItems = Auth::check()
        ? CartItem::where('UserId', Auth::id())->get()
        : session('guest_cart', []);

    if (empty($cartItems)) {
        return response()->json(['error' => 'Sepetiniz boş!'], 400);
    }

    // Campaign tablosundan kuponu bul
    $coupon = Campaign::where('CouponCode', $couponCode)
                      ->where('IsActive', 1)
                      ->where('StartDate', '<=', now())
                      ->where('EndDate', '>=', now())
                      ->first();

    if (!$coupon) {
        return response()->json(['error' => 'Kupon geçersiz veya süresi dolmuş.'], 400);
    }

    // Sepet toplamını hesapla
    $totalAmount = 0;
    if (Auth::check()) {
        $totalAmount = $cartItems->sum('TotalPrice');
    } else {
        foreach ($cartItems as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }
    }

    // MinCartTotal kontrolü
    if ($coupon->MinCartTotal && $totalAmount < $coupon->MinCartTotal) {
        return response()->json(['error' => 'Sepet toplamınız kuponu kullanmak için yeterli değil.'], 400);
    }

    // İndirim hesaplama
    if ($coupon->DiscountType === 'percentage') {
        $discount = $totalAmount * ($coupon->DiscountValue / 100);
    } else {
        $discount = $coupon->DiscountValue;
    }

    $newTotal = max($totalAmount - $discount, 0);

    return response()->json([
        'success' => true,
        'discount' => $discount,
        'total_after_discount' => $newTotal,
        'message' => "Kupon uygulandı! {$discount} ₺ indirim kazandınız."
    ]);
}

}