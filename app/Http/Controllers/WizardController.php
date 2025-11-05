<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCriteria;
use App\Models\CriteriaCompatibility;
use Illuminate\Support\Facades\Auth; 
use App\Models\BuildItem;
use App\Models\Build;
use App\Models\Configuration;
use App\Models\CartItem;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignProduct;
use App\Models\CampaignSubCategory;

class WizardController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('CategoryId')->get();
        $selectedProducts = session('selected_products', []);
        
        // Sepet sayısını al
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');
        }
        
        return view('wizard.index', compact('categories', 'selectedProducts', 'cartCount'));
    }

    public function selectProduct(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Ürün bulunamadı'], 404);
        }

        $selectedProducts = session('selected_products', []);

        // Aynı kategori varsa önce sil
        $selectedProducts = array_filter($selectedProducts, function($item) use ($product) {
            return $item['CategoryId'] != $product->CategoryId;
        });

        // Yeni ürünü ekle
        $selectedProducts[] = [
            'ProductId' => $product->ProductId,
            'CategoryId' => $product->CategoryId,
            'Ad' => $product->Ad,
            'Fiyat' => $product->Fiyat,
            'Resim' => $product->Resim,
            'Marka' => $product->Marka,
            'Model' => $product->Model
        ];

        session(['selected_products' => $selectedProducts]);

        $totalPrice = array_sum(array_column($selectedProducts, 'Fiyat'));

        return response()->json([
            'success' => true,
            'selected_products' => $selectedProducts,
            'total_price' => $totalPrice
        ]);
    }

    public function removeProduct(Request $request)
    {
        $categoryId = $request->category_id;
        $selectedProducts = session('selected_products', []);

        $selectedProducts = array_filter($selectedProducts, function($item) use ($categoryId) {
            return $item['CategoryId'] != $categoryId;
        });

        session(['selected_products' => $selectedProducts]);

        $totalPrice = array_sum(array_column($selectedProducts, 'Fiyat'));

        return response()->json([
            'success' => true,
            'selected_products' => $selectedProducts,
            'total_price' => $totalPrice
        ]);
    }

    public function getProducts($categoryId)
    {
        $selectedProducts = session('selected_products', []);

        $products = Product::where('CategoryId', $categoryId)->get();

        $allProductIds = $products->pluck('ProductId')->toArray();
        $selectedProductIds = array_column($selectedProducts, 'ProductId');
        $allRelevantProductIds = array_merge($allProductIds, $selectedProductIds);

        $allCriteria = ProductCriteria::whereIn('ProductId', $allRelevantProductIds)
                                      ->get()->groupBy('ProductId');

        foreach ($products as $product) {
            $currentProductCriteria = $allCriteria->get($product->ProductId, collect());
            $isCompatible = true;

            foreach ($selectedProducts as $selectedProduct) {
                if ($selectedProduct['CategoryId'] == $categoryId) continue;

                $selectedProductCriteria = $allCriteria->get($selectedProduct['ProductId'], collect());

                if (!$this->areProductsCompatible(
                    $currentProductCriteria,
                    $selectedProductCriteria,
                    $product->CategoryId,
                    $selectedProduct['CategoryId']
                )) {
                    $isCompatible = false;
                    break;
                }
            }

            $product->is_compatible = $isCompatible;
        }

        return response()->json([
            'products' => $products,
            'category' => Category::find($categoryId)
        ]);
    }

    private function areProductsCompatible($product1Criteria, $product2Criteria, $product1CategoryId = null, $product2CategoryId = null)
    {
        $GPU_CATEGORY_ID = 4; // Ekran kartı
        $MOTHERBOARD_CATEGORY_ID = 2; // Anakart

        // Eğer GPU ve Anakart değilse klasik uyumluluk
        if ($product1CategoryId != $GPU_CATEGORY_ID && $product2CategoryId != $GPU_CATEGORY_ID) {
            foreach ($product1Criteria as $c1) {
                foreach ($product2Criteria as $c2) {
                    $val1 = strtolower(trim((string)$c1->CriteriaValue));
                    $val2 = strtolower(trim((string)$c2->CriteriaValue));

                    $compatibility = CriteriaCompatibility::where(function($q) use ($c1, $c2, $val1, $val2) {
                        $q->where('CriteriaId1', $c1->CriteriaId)
                          ->where('CriteriaValue1', $val1)
                          ->where('CriteriaId2', $c2->CriteriaId)
                          ->where('CriteriaValue2', $val2);
                    })->orWhere(function($q) use ($c1, $c2, $val1, $val2) {
                        $q->where('CriteriaId1', $c2->CriteriaId)
                          ->where('CriteriaValue1', $val2)
                          ->where('CriteriaId2', $c1->CriteriaId)
                          ->where('CriteriaValue2', $val1);
                    })->exists();

                    if ($compatibility) return true;
                }
            }
            return false;
        }

        // GPU ↔ Anakart eşleşmesi
        if (($product1CategoryId == $GPU_CATEGORY_ID && $product2CategoryId == $MOTHERBOARD_CATEGORY_ID) ||
            ($product2CategoryId == $GPU_CATEGORY_ID && $product1CategoryId == $MOTHERBOARD_CATEGORY_ID)) {

            $gpuCriteria = $product1CategoryId == $GPU_CATEGORY_ID ? $product1Criteria : $product2Criteria;
            $moboCriteria = $product1CategoryId == $MOTHERBOARD_CATEGORY_ID ? $product1Criteria : $product2Criteria;

            // GPU ve Anakart Express sürümlerini eşitlik ile kontrol et
            foreach ($gpuCriteria as $cGpu) {
                foreach ($moboCriteria as $cMobo) {
                    if (strtolower(trim((string)$cGpu->CriteriaValue)) === strtolower(trim((string)$cMobo->CriteriaValue))) {
                        return true;
                    }
                }
            }
            return false; // Eğer hiçbir eşleşme yoksa uyumsuz
        }

        return true;
    }

    /*public function addToCart(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Giriş yapmalısınız'], 401);

        $product = Product::find($request->product_id);
        if (!$product) return response()->json(['error' => 'Ürün bulunamadı'], 404);

        $cartItem = CartItem::firstOrNew([
            'UserId' => Auth::id(),
            'ProductId' => $product->ProductId
        ]);

        $cartItem->Quantity = ($cartItem->Quantity ?? 0) + 1;
        $cartItem->UnitPrice = $product->Fiyat;
        $cartItem->TotalPrice = $cartItem->Quantity * $cartItem->UnitPrice;
        $cartItem->ProductName = $product->Ad;
        $cartItem->ProductImage = $product->Resim;
        $cartItem->ProductBrand = $product->Marka;
        $cartItem->ProductModel = $product->Model;
        $cartItem->CategoryId = $product->CategoryId;
        $cartItem->save();

        $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');

        return response()->json([
            'success' => true,
            'message' => 'Ürün sepete eklendi',
            'cart_count' => $cartCount
        ]);
    }*/

    
    public function addToCart(Request $request)
{
    if (!Auth::check()) return response()->json(['error' => 'Giriş yapmalısınız'], 401);

    $product = Product::find($request->product_id);
    if (!$product) return response()->json(['error' => 'Ürün bulunamadı'], 404);

    $stock = \App\Models\Stock::where('ProductId', $product->ProductId)->first();
    if (!$stock || $stock->Miktar < 1) {
        return response()->json(['error' => 'Üzgünüz, bu üründen stokta kalmadı.'], 400);
    }

    $cartItem = CartItem::firstOrNew([
        'UserId' => Auth::id(),
        'ProductId' => $product->ProductId
    ]);

    // Maksimum stok kadar ekle
    $newQuantity = ($cartItem->Quantity ?? 0) + 1;
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

    return response()->json([
        'success' => true,
        'message' => $message,
        'cart_count' => $cartCount
    ]);
}


    public function addAllToCart(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Giriş yapmalısınız'], 401);

        $selectedProducts = session('selected_products', []);
        if (empty($selectedProducts)) return response()->json(['error' => 'Seçili ürün bulunmuyor'], 400);

        foreach ($selectedProducts as $productData) {
            $cartItem = CartItem::firstOrNew([
                'UserId' => Auth::id(),
                'ProductId' => $productData['ProductId']
            ]);

            $cartItem->Quantity = ($cartItem->Quantity ?? 0) + 1;
            $cartItem->UnitPrice = $productData['Fiyat'];
            $cartItem->TotalPrice = $cartItem->Quantity * $cartItem->UnitPrice;
            $cartItem->ProductName = $productData['Ad'];
            $cartItem->ProductImage = $productData['Resim'];
            $cartItem->ProductBrand = $productData['Marka'];
            $cartItem->ProductModel = $productData['Model'];
            $cartItem->CategoryId = $productData['CategoryId'];
            $cartItem->save();
        }

        $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');

        return response()->json([
            'success' => true,
            'message' => 'Tüm ürünler sepete eklendi',
            'cart_count' => $cartCount
        ]);
    }

    public function saveConfiguration(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Giriş yapmalısınız'], 401);
            }

            $configName = $request->config_name;
            $selectedProducts = session('selected_products', []);

            if (empty($selectedProducts)) {
                return response()->json(['error' => 'Seçili ürün bulunmuyor'], 400);
            }

            if (empty($configName)) {
                return response()->json(['error' => 'Konfigürasyon adı gerekli'], 400);
            }

            $totalPrice = array_sum(array_column($selectedProducts, 'Fiyat'));

            // Build kaydını oluştur
            $build = Build::create([
                'UserId' => Auth::id(),
                'Name' => $configName,
                'CreatedDate' => now(),
                'TotalPrice' => $totalPrice,
            ]);

            // Her ürün için BuildItem oluştur
            foreach ($selectedProducts as $product) {
                BuildItem::create([
                    'BuildId' => $build->BuildId,
                    'CategoryId' => $product['CategoryId'],
                    'ProductId' => $product['ProductId'],
                    'Quantity' => 1,
                    'AddedDate' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Konfigürasyon başarıyla kaydedildi',
                'build_id' => $build->BuildId,
                'config_name' => $configName,
                'total_price' => $totalPrice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Hata oluştu: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function getCart()
    {
        if (!Auth::check()) {
            return response()->json(['cart_items' => [], 'total_amount' => 0]);
        }

        $cartItems = CartItem::where('UserId', Auth::id())->get();
        $totalAmount = $cartItems->sum('TotalPrice');

        return response()->json([
            'cart_items' => $cartItems,
            'total_amount' => $totalAmount,
            'cart_count' => $cartItems->sum('Quantity')
        ]);
    }

    public function removeFromCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Giriş yapmalısınız'], 401);
        }

        $cartItemId = $request->input('cart_item_id'); // Burada da input() kullan

        $cartItem = CartItem::where('CartItemId', $cartItemId)
                           ->where('UserId', Auth::id())
                           ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Sepet öğesi bulunamadı'], 404);
        }

        try {
            $cartItem->delete();
            $cartCount = CartItem::where('UserId', Auth::id())->sum('Quantity');

            return response()->json([
                'success' => true,
                'message' => 'Ürün sepetten kaldırıldı',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ürün sepetten kaldırılamadı'], 500);
        }
    }


    public function getConfigurations()
    {
        if (!Auth::check()) {
            return response()->json(['configurations' => []]);
        }

        $configurations = Build::where('UserId', Auth::id())
                               ->with('items.product') // BuildItem ve product ilişkilerini yükle
                               ->orderBy('CreatedDate', 'desc')
                               ->get();

        return response()->json([
            'configurations' => $configurations
        ]);
    }

    public function loadConfiguration(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Giriş yapmalısınız'], 401);
            }

            $buildId = $request->input('config_id'); // POST ile gelen id

            $build = Build::where('UserId', Auth::id())
                          ->where('BuildId', $buildId)
                          ->with('items.product')
                          ->firstOrFail();

            $selectedProducts = [];
            foreach ($build->items as $item) {
                if ($item->product) {
                    $selectedProducts[] = [
                        'ProductId' => $item->product->ProductId,
                        'Ad' => $item->product->Ad,
                        'Resim' => $item->product->Resim,
                        'Fiyat' => $item->product->Fiyat,
                        'Marka' => $item->product->Marka,
                        'Model' => $item->product->Model,
                        'CategoryId' => $item->product->CategoryId,
                    ];
                }
            }

            session(['selected_products' => $selectedProducts]);

            return response()->json([
                'success' => true,
                'message' => 'Konfigürasyon başarıyla yüklendi.',
                'configuration' => $build,
                'selected_products' => $selectedProducts,
                'config_name' => $build->Name
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Konfigürasyon yüklenemedi: ' . $e->getMessage()], 500);
        }
    }

    public function applyCoupon(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,OrderId',
        'coupon_code' => 'required|string|max:50'
    ]);

    $order = \App\Models\Order::where('OrderId', $request->order_id)->first();
    if (!$order) {
        return response()->json(['error' => 'Sipariş bulunamadı!'], 404);
    }

    // Veritabanındaki aktif kuponu çek
    $coupon = \App\Models\Campaign::where('CouponCode', strtoupper($request->coupon_code))
                                   ->where('IsActive', 1)
                                   ->first();

    if (!$coupon) {
        return response()->json(['error' => 'Geçersiz veya aktif olmayan kupon!']);
    }

    // Minimum sepet toplamı varsa kontrol et
    if ($coupon->MinCartTotal && $order->TotalAmount < $coupon->MinCartTotal) {
        return response()->json(['error' => "Kupon için minimum sepet tutarı {$coupon->MinCartTotal} ₺ olmalı."]);
    }

    // İndirim hesapla
    if ($coupon->DiscountType === 'sabit') {
        $discount = $coupon->DiscountValue;
    } else { // yuzde
        $discount = $order->TotalAmount * ($coupon->DiscountValue / 100);
    }

    $newTotal = $order->TotalAmount - $discount;

    return response()->json([
        'success' => true,
        'discount_text' => number_format($discount, 2, ',', '.') . ' ₺',
        'new_total_formatted' => number_format($newTotal, 2, ',', '.')
    ]);
}




    // deleteConfiguration metodunu düzelt (mevcut metodu değiştir)
    public function deleteConfiguration(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Giriş yapmalısınız'], 401);
            }

            $buildId = $request->input('config_id');
            
            $build = Build::where('UserId', Auth::id())
                          ->where('BuildId', $buildId)
                          ->first();

            if (!$build) {
                return response()->json(['error' => 'Konfigürasyon bulunamadı'], 404);
            }

            // Transaction ile güvenli silme
            \DB::transaction(function() use ($buildId) {
                // İlk önce BuildItem'ları sil
                BuildItem::where('BuildId', $buildId)->delete();
                
                // Sonra Build'i sil
                Build::where('BuildId', $buildId)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Konfigürasyon başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Konfigürasyon silinemedi: ' . $e->getMessage()], 500);
        }
    }

   // showSummary metodunu düzelt
    public function showSummary($configId)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız.');
            }

            $build = Build::where('UserId', Auth::id())
                          ->where('BuildId', $configId)
                          ->with(['items.product.category'])
                          ->firstOrFail();

            $products = collect();
            foreach ($build->items as $item) {
                if ($item->product) {
                    $products->push($item->product);
                }
            }

            return view('wizard.summary', compact('build', 'products'));
        } catch (\Exception $e) {
            return redirect()->route('wizard.index')->with('error', 'Konfigürasyon bulunamadı.');
        }
    }



    public function completeOrder(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Giriş yapmalısınız'], 401);
    }

    // Sepetten ürünleri al
    $cartItems = CartItem::where('UserId', Auth::id())->get();
    
    if ($cartItems->isEmpty()) {
        return response()->json(['error' => 'Sepetiniz boş!'], 400);
    }

    try {
        \DB::beginTransaction();

        // 1. Siparişi oluştur
        $order = \App\Models\Order::create([
            'UserId' => Auth::id(),
            'OrderNumber' => 'SIP-' . time() . rand(1000, 9999),
            'OrderStatus' => 'Pending',
            'TotalAmount' => $cartItems->sum('TotalPrice'),
            'OrderDate' => now(),
        ]);

        // 2. Order Items ekle
        foreach ($cartItems as $cartItem) {
            \App\Models\OrderItem::create([
                'OrderId' => $order->OrderId,
                'ProductId' => $cartItem->ProductId,
                'Quantity' => $cartItem->Quantity,
                'UnitPrice' => $cartItem->UnitPrice,
                'TotalPrice' => $cartItem->TotalPrice,
                'ProductName' => $cartItem->ProductName,
                'ProductImage' => $cartItem->ProductImage,
                'ProductBrand' => $cartItem->ProductBrand,
                'ProductModel' => $cartItem->ProductModel,
            ]);
        }

        \DB::commit();

        return response()->json([
            'success' => true,
            'order_id' => $order->OrderId,
            'redirect_url' => route('wizard.payment', ['orderId' => $order->OrderId])
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json(['error' => 'Sipariş oluşturulamadı: ' . $e->getMessage()], 500);
    }
}

public function showPaymentPage($orderId)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $order = \App\Models\Order::where('OrderId', $orderId)
                             ->where('UserId', Auth::id())
                             ->with('items')
                             ->firstOrFail();

    return view('wizard.payment', compact('order'));
}

/*public function processPayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:Orders,OrderId',
        'card_number' => 'required|string|min:16|max:19',
        'card_name' => 'required|string|max:100',
        'expiry_month' => 'required|numeric|min:1|max:12',
        'expiry_year' => 'required|numeric|min:2024',
        'cvv' => 'required|numeric|digits:3',
        'billing_address' => 'required|string|max:255',
        'billing_city' => 'required|string|max:100',
        'billing_phone' => 'required|string|max:20',
    ]);

    if (!Auth::check()) {
        return response()->json(['error' => 'Giriş yapmalısınız'], 401);
    }

    $order = \App\Models\Order::where('OrderId', $request->order_id)
                             ->where('UserId', Auth::id())
                             ->first();

    if (!$order) {
        return response()->json(['error' => 'Sipariş bulunamadı'], 404);
    }

    try {
        \DB::beginTransaction();

        // Ödeme işlemi simülasyonu (gerçek projede ödeme gateway'i entegrasyonu yapılır)
        $paymentSuccess = $this->simulatePayment($request->all(), $order->TotalAmount);

        if ($paymentSuccess) {
            // Sipariş durumunu güncelle
            $order->update([
                'OrderStatus' => 'Paid',
                'PaymentDate' => now(),
                'PaymentMethod' => 'Credit Card'
            ]);

            // Sepeti temizle
            CartItem::where('UserId', Auth::id())->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla tamamlandı!',
                'order_number' => $order->OrderNumber,
                'redirect_url' => route('wizard.order.success', ['orderId' => $order->OrderId])
            ]);
        } else {
            throw new \Exception('Ödeme işlemi başarısız');
        }

    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json(['error' => 'Ödeme işlemi başarısız: ' . $e->getMessage()], 500);
    }
}*/

public function processPayment(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:Orders,OrderId',
        'card_number' => 'required|string|min:16|max:19',
        'card_name' => 'required|string|max:100',
        'expiry_month' => 'required|numeric|min:1|max:12',
        'expiry_year' => 'required|numeric|min:2024',
        'cvv' => 'required|numeric|digits:3',
        'billing_address' => 'required|string|max:255',
        'billing_city' => 'required|string|max:100',
        'billing_phone' => 'required|string|max:20',
        'coupon_code' => 'nullable|string|max:50', // kupon alanı
    ]);

    if (!Auth::check()) {
        return response()->json(['error' => 'Giriş yapmalısınız'], 401);
    }

    $order = \App\Models\Order::where('OrderId', $request->order_id)
                             ->where('UserId', Auth::id())
                             ->with('items')
                             ->first();

    if (!$order) {
        return response()->json(['error' => 'Sipariş bulunamadı'], 404);
    }

    // 1. Stok kontrolü
    foreach ($order->items as $item) {
        $stock = \App\Models\Stock::where('ProductId', $item->ProductId)->first();
        if (!$stock || $stock->Miktar < $item->Quantity) {
            return response()->json([
                'error' => "Üzgünüz, '{$item->ProductName}' ürününden yeterli stok yok. Mevcut stok: " . ($stock->Miktar ?? 0)
            ], 400);
        }
    }

    // 2. Kupon kontrolü ve toplam güncelleme
    $totalAmount = $order->TotalAmount;
    if ($request->filled('coupon_code')) {
        $coupon = \App\Models\Campaign::where('CouponCode', $request->coupon_code)
                                       ->where('IsActive', 1)
                                       ->whereDate('StartDate', '<=', now())
                                       ->whereDate('EndDate', '>=', now())
                                       ->first();
        if (!$coupon) {
            return response()->json(['error' => 'Geçersiz veya süresi dolmuş kupon!'], 400);
        }

        // İndirimi uygula
        if ($coupon->DiscountType === 'yuzde') {
            $totalAmount = $totalAmount * (1 - $coupon->DiscountValue / 100);
        } elseif ($coupon->DiscountType === 'sabit') {
            $totalAmount = max(0, $totalAmount - $coupon->DiscountValue);
        } elseif ($coupon->DiscountType === 'ucretsiz_kargo') {
            // Örnek: kargo ücreti varsa düşebilirsin
        }
        // Eğer paket vs başka türler varsa buraya ekle
    }

    try {
        \DB::beginTransaction();

        // 3. Ödeme işlemi simülasyonu
        $paymentSuccess = $this->simulatePayment($request->all(), $totalAmount);

        if ($paymentSuccess) {
            // 4. Sipariş durumunu güncelle ve toplamı kaydet
            $order->update([
                'OrderStatus' => 'Paid',
                'PaymentDate' => now(),
                'PaymentMethod' => 'Credit Card',
                'TotalAmount' => $totalAmount
            ]);

            // 5. Stokları eksilt
            foreach ($order->items as $item) {
                $stock = \App\Models\Stock::where('ProductId', $item->ProductId)->first();
                if ($stock) {
                    $stock->Miktar = max($stock->Miktar - $item->Quantity, 0);
                    $stock->GuncellemeTarihi = now();
                    $stock->save();
                }
            }

            // 6. Sepeti temizle
            CartItem::where('UserId', Auth::id())->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla tamamlandı! Toplam: ' . number_format($totalAmount, 2, ',', '.') . ' ₺',
                'order_number' => $order->OrderNumber,
                'redirect_url' => route('wizard.order.success', ['orderId' => $order->OrderId])
            ]);
        } else {
            throw new \Exception('Ödeme işlemi başarısız');
        }

    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json(['error' => 'Ödeme işlemi başarısız: ' . $e->getMessage()], 500);
    }
}



private function simulatePayment($paymentData, $amount)
{
    // Basit ödeme simülasyonu
    // Gerçek projede burada ödeme gateway API'si çağrılır
    
    // Kart numarası kontrolleri
    $cardNumber = str_replace(' ', '', $paymentData['card_number']);
    
    // Test kartları (başarılı ödeme)
    $successfulCards = ['4111111111111111', '5555555555554444', '4000000000000002'];
    
    if (in_array($cardNumber, $successfulCards)) {
        sleep(2); // Ödeme işlemi simülasyonu
        return true;
    }
    
    // Başarısız kart (4000000000000069)
    if ($cardNumber === '4000000000000069') {
        return false;
    }
    
    // Diğer kartlar için random başarı
    return rand(1, 10) > 2; // %80 başarı oranı
}

public function orderSuccess($orderId)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $order = \App\Models\Order::where('OrderId', $orderId)
                             ->where('UserId', Auth::id())
                             ->with('items')
                             ->firstOrFail();

    return view('wizard.order-success', compact('order'));
}

    public function clearSelection()
    {
        session()->forget('selected_products');
        return response()->json(['success' => true]);
    }
}