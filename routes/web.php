<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrunController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\KriterController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CriteriaCompatibilityController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\Admin\StockLogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\CampaignController;
// Ana sayfa
Route::get('/', [UrunController::class, 'index'])->name('home');
Route::get('/product/{id}/details', [UrunController::class, 'showDetails'])->name('product.details');



// Kategori ID'si ile ürünleri listelemek için yeni rota
Route::get('/kategori/{categoryId}', [UrunController::class, 'kategoriyeGoreUrunleriGoster'])->name('kategori.urunleri');

// Alt kategori ID'si ile ürünleri listelemek için yeni rota ekle
// Bu rota, alt kategorilere tıklandığında ilgili ürünleri gösterecektir.
Route::get('/alt-kategori/{subCategoryId}', [UrunController::class, 'altKategoriyeGoreUrunleriGoster'])->name('altkategori.urunleri');

// Ürünler sayfası
Route::get('/urunler', [UrunController::class, 'urunlerSayfasi'])->name('urunler');

Route::get('/subcategories/{categoryId}', [UrunController::class, 'getSubcategories']);


// UrunController için cart ve order rotaları
// Sepet işlemleri
Route::post('/urun/add-to-cart', [UrunController::class, 'addToCart'])->name('urun.add-to-cart');
Route::get('/urun/cart', [UrunController::class, 'getCart'])->name('urun.cart');
Route::post('/urun/remove-from-cart', [UrunController::class, 'removeFromCart'])->name('urun.remove-from-cart');
Route::get('/urun/get-cart', [UrunController::class, 'getCart'])->name('urun.get-cart');

Route::post('/urun/merge-cart', [UrunController::class, 'mergeSessionCart'])->name('urun.merge-cart');
Route::post('/urun/guest-order', [UrunController::class, 'createGuestOrder'])->name('urun.guest-order');
Route::get('/urun/guest-payment/{orderId}', [UrunController::class, 'showGuestPaymentPage'])->name('guest.payment');
Route::post('/urun/guest-process-payment', [UrunController::class, 'processGuestPayment'])->name('guest.process-payment');
Route::get('/urun/guest-order-success/{orderId}', [UrunController::class, 'guestOrderSuccess'])->name('guest.order.success');

// Checkout sayfası
Route::get('/checkout', [UrunController::class, 'showCheckout'])->name('checkout');
// Ürün detay sayfası (modal için AJAX)
Route::get('/urun/detay/{id}', [UrunController::class, 'getDetails'])->name('urun.detay');

// Dashboard ve Profil
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =======================
// PC Toplama Sihirbazı Route'ları
// =======================
Route::prefix('wizard')->name('wizard.')->group(function () {
    // Sihirbazın ana sayfası
    Route::get('/', [WizardController::class, 'index'])->name('index');

    
    // Ürün listeleme
    Route::get('/products/{categoryId}', [WizardController::class, 'getProducts'])->name('products');
    
    // AJAX İstekleri
    Route::post('/select', [WizardController::class, 'selectProduct'])->name('select');
    Route::post('/remove', [WizardController::class, 'removeProduct'])->name('remove');
    Route::post('/clear', [WizardController::class, 'clearSelection'])->name('clear');
    
    // Giriş yapmış kullanıcılar için
    Route::middleware('auth')->group(function () {
        // Sepet işlemleri
        Route::post('/add-to-cart', [WizardController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/add-all-to-cart', [WizardController::class, 'addAllToCart'])->name('add-all-to-cart');
        Route::get('/cart', [WizardController::class, 'getCart'])->name('cart');
        Route::post('/remove-from-cart', [WizardController::class, 'removeFromCart'])->name('remove-from-cart');
        
        //YENİ ÖDEME 
        // YENİ ÖDEME ROUTE'LARI
    Route::post('/complete-order', [WizardController::class, 'completeOrder'])->name('complete-order');
    Route::get('/payment/{orderId}', [WizardController::class, 'showPaymentPage'])->name('payment');
    Route::post('/process-payment', [WizardController::class, 'processPayment'])->name('process-payment');
    Route::get('/order-success/{orderId}', [WizardController::class, 'orderSuccess'])->name('order.success');
    //Route::post('/wizard/complete-order', [WizardController::class, 'completeOrder'])->name('wizard.complete-order');


        // Konfigürasyon işlemleri
        Route::post('/save-configuration', [WizardController::class, 'saveConfiguration'])->name('save-configuration');
        Route::get('/configurations', [WizardController::class, 'getConfigurations'])->name('configurations');
        
        // Konfigürasyon yükleme ve silme - parametreyi kaldırdım
        Route::post('/load-configuration', [WizardController::class, 'loadConfiguration'])->name('load-configuration');
        Route::post('/delete-configuration', [WizardController::class, 'deleteConfiguration'])->name('delete-configuration');

        //Route::post('/wizard/complete-order', [WizardController::class, 'completeOrder'])->name('wizard.completeOrder')->middleware('auth');
     



       // Route::get('/summary', [WizardController::class, 'summary'])->name('wizard.summary');


    });
});
 Route::get('/summary/{configId}', [WizardController::class, 'showSummary'])->name('wizard.summary');
// =======================
// Admin route'ları
// =======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Kullanıcılar
    Route::get('/users', function () {
        $users = \App\Models\User::all();
        return view('admin.users', compact('users'));
    })->name('users');
    
    // Kategoriler
    Route::resource('categories', CategoryController::class);
    
    // Alt Kategoriler
    Route::resource('subcategories', SubCategoryController::class);
    
    // Kriterler
    Route::resource('kriterler', KriterController::class);
    
    // Ürünler
    Route::get('products', [ProductController::class,'index'])->name('products.index');
    Route::get('products/create', [ProductController::class,'create'])->name('products.create');
    Route::post('products/store', [ProductController::class,'store'])->name('products.store');
    Route::get('products/{id}/edit', [ProductController::class,'edit'])->name('products.edit');
    Route::post('products/{id}/update', [ProductController::class,'update'])->name('products.update');
    Route::delete('products/{id}/delete', [ProductController::class,'destroy'])->name('products.destroy');
    
    // AJAX rotaları
    Route::get('/subcategories-by-category/{categoryId}', [SubCategoryController::class, 'getByCategory'])->name('subcategories.byCategory');
    Route::get('/criterias-by-subcategory/{subCategoryId}', [ProductController::class, 'getCriteriasBySubCategory'])->name('criterias.bySubCategory');
    
    // Criteria Compatibility
    Route::resource('criteria_compatibilities', CriteriaCompatibilityController::class);
    Route::get('criteria_values/{id}', [CriteriaCompatibilityController::class, 'getCriteriaValues'])->name('criteria_values.get');
});



Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // Kullanıcı siparişleri
    Route::get('/user/orders', function() {
        $orders = \App\Models\Order::where('UserId', Auth::id())
                                  ->with('items')
                                  ->orderBy('OrderDate', 'desc')
                                  ->get();
        return response()->json(['orders' => $orders]);
    })->name('user.orders');
    
    // Sipariş detayı
    Route::get('/orders/{orderId}', function($orderId) {
        $order = \App\Models\Order::where('OrderId', $orderId)
                                 ->where('UserId', Auth::id())
                                 ->with('items')
                                 ->first();
        
        if (!$order) {
            return response()->json(['error' => 'Sipariş bulunamadı'], 404);
        }
        
        return response()->json($order);
    })->name('orders.show');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function() {
    Route::get('stock_logs', [App\Http\Controllers\Admin\StockLogController::class, 'index'])->name('stock_logs.index');
    Route::get('stock_logs/create', [App\Http\Controllers\Admin\StockLogController::class, 'create'])->name('stock_logs.create');
    Route::post('stock_logs', [App\Http\Controllers\Admin\StockLogController::class, 'store'])->name('stock_logs.store');
});



Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('orders', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
});






Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('messages', [App\Http\Controllers\Admin\AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{id}', [App\Http\Controllers\Admin\AdminMessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{id}', [App\Http\Controllers\Admin\AdminMessageController::class, 'destroy'])->name('messages.destroy');
});




//Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

//ARAMA KISMI:
// Arama route'ları
Route::get('/search', [SearchController::class, 'search']);
Route::get('/search/live', [SearchController::class, 'liveSearch']);


// web.php routes - Bu rotaları ekleyin
Route::get('/urun/{id}', [UrunController::class, 'show'])->name('product.show');
Route::get('/urun/{id}/details', [UrunController::class, 'getDetails'])->name('product.details');
Route::post('/urun/toggle-wishlist', [UrunController::class, 'toggleWishlist']);
// Kupon kontrolü
// Kupon kontrolü (UrunController içinde)
Route::post('/urun/apply-coupon', [UrunController::class, 'applyCoupon'])->name('apply.coupon');





Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Kampanyalar
    Route::get('campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('campaigns/{id}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
});


Route::post('/apply-coupon', [WizardController::class, 'applyCoupon'])->name('wizard.apply-coupon');
Route::post('/complete-payment', [WizardController::class, 'completePayment'])->name('wizard.complete-payment');

require __DIR__.'/auth.php';