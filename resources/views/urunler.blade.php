@extends('layouts.frontend')

@section('title', 'Ürünler - Avantaj Bilişim')

@push('styles')
<style>
    .product-card { 
        transition: all 0.3s ease; 
        border: 1px solid #e5e7eb; 
    }
    .product-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 15px 30px rgba(0,0,0,0.1); 
        border-color: #ef4444; 
    }
    .btn-primary { 
        background: linear-gradient(135deg, #ef4444, #dc2626); 
        transition: all 0.3s ease; 
    }
    .btn-primary:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(239, 68, 68, 0.3); 
    }
    .bounce { 
        animation: bounce 0.5s; 
    }
    @keyframes bounce { 
        0%,20%,60%,100%{transform:translateY(0);}
        40%{transform:translateY(-10px);}
        80%{transform:translateY(-5px);} 
    }
</style>
@endpush

@section('content')

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="flex items-center mb-6">
        <a href="/" class="text-gray-500 hover:text-red-500 mr-2"><i class="fas fa-home"></i></a>
        <span class="text-gray-400 mr-2">></span>
        <span class="text-gray-700 font-medium">Ürünler</span>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        <i class="fas fa-boxes mr-3 text-red-500"></i>Ürünler
    </h1>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">
            <i class="fas fa-filter mr-2 text-red-500"></i>Filtreler
        </h3>
        <form method="GET" action="{{ route('urunler') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <select name="category" id="categorySelect" class="p-3 border rounded-lg">
                <option value="">Kategori Seç</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->CategoryId }}" {{ request('category') == $cat->CategoryId ? 'selected' : '' }}>
                        {{ $cat->CategoryName }}
                    </option>
                @endforeach
            </select>

            <select name="subcategory" id="subcategorySelect" class="p-3 border rounded-lg">
                <option value="">Alt Kategori Seç</option>
                @if(request('category'))
                    @php $selectedCategory = $categories->firstWhere('CategoryId', request('category')); @endphp
                    @if($selectedCategory && $selectedCategory->subcategories)
                        @foreach($selectedCategory->subcategories as $sub)
                            <option value="{{ $sub->SubCategoryId }}" {{ request('subcategory') == $sub->SubCategoryId ? 'selected' : '' }}>
                                {{ $sub->SubCategoryName }}
                            </option>
                        @endforeach
                    @endif
                @endif
            </select>

            <select name="brand" class="p-3 border rounded-lg">
                <option value="">Marka Seç</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
            </select>

            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Fiyat" class="p-3 border rounded-lg">
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Fiyat" class="p-3 border rounded-lg">

            <div class="lg:col-span-2 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ürün Ara..." class="flex-1 p-3 border rounded-lg">
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-search mr-1"></i>Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer" data-product-id="{{ $product->ProductId }}">
                <div class="relative">
                    <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" alt="{{ $product->Ad }}" class="w-full h-48 object-contain p-4">
                    <button class="wishlist-btn absolute top-2 right-2 p-2 bg-white rounded-full shadow">
    <i class="far fa-heart text-gray-400 hover:text-red-500"></i>
</button>
                    <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">Yeni</div>
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 hover:text-red-600 transition-colors">
                        {{ $product->Marka }} {{ $product->Model }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->Ad }}</p>

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-red-500">
                            {{ number_format($product->Fiyat, 2, ',', '.') }} ₺
                        </span>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star text-sm"></i>
                            <i class="fas fa-star text-sm"></i>
                            <i class="fas fa-star text-sm"></i>
                            <i class="fas fa-star text-sm"></i>
                            <i class="far fa-star text-sm"></i>
                            <span class="text-xs text-gray-500 ml-1">(24)</span>
                        </div>
                    </div>

                 <div class="flex items-center space-x-2">
    <div class="flex items-center border border-gray-300 rounded-lg">
        <button class="quantity-btn px-3 py-1 hover:bg-gray-100" data-action="minus" data-product-id="{{ $product->ProductId }}">
            <i class="fas fa-minus text-sm"></i>
        </button>
        <input type="number" class="quantity-input w-12 text-center border-0 bg-transparent" value="1" min="1" data-product-id="{{ $product->ProductId }}">
        <button class="quantity-btn px-3 py-1 hover:bg-gray-100" data-action="plus" data-product-id="{{ $product->ProductId }}">
            <i class="fas fa-plus text-sm"></i>
        </button>
    </div>

    <button class="add-to-cart-btn flex-1 btn-primary text-white py-2 px-4 rounded-lg font-semibold text-sm" data-product-id="{{ $product->ProductId }}">
        <i class="fas fa-shopping-cart mr-1"></i>Sepete Ekle
    </button>
</div>
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                        <span><i class="fas fa-truck mr-1"></i>Ücretsiz Kargo</span>
                        <span><i class="fas fa-shield-alt mr-1"></i>2 Yıl Garanti</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg text-gray-500 mb-2">Aradığınız kriterlere uygun ürün bulunamadı.</p>
                <p class="text-gray-400">Lütfen filtre seçimlerinizi değiştirip tekrar deneyin.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($products) && $products->hasPages())
        <div class="mt-12">{{ $products->links() }}</div>
    @endif
</div>

<!-- Product Details Modal -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Ürün Detayları</h2>
                <button id="closeProductModal" class="text-white hover:text-gray-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="product-details-content">
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-red-500"></i>
                    <p class="mt-4 text-gray-600">Yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart Modal -->
<div id="cartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Sepetim</h2>
                <button id="closeCartModal" class="text-white hover:text-gray-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]" id="cart-content">
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
                <p class="mt-4 text-gray-600">Yükleniyor...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// jQuery yüklü mü kontrol et
if (typeof jQuery === 'undefined') {
    console.error('HATA: jQuery yüklenmemiş!');
} else {
    console.log('jQuery yüklendi, versiyon:', jQuery.fn.jquery);
}

// Sayfa tamamen yüklenince çalıştır
$(document).ready(function() {
    console.log('Document ready - script başlatıldı');
    
    let cartCount = 0;

    // CSRF Token ayarla
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Sepet badge'ini güncelle
    function updateCartBadge() {
        $.ajax({
            url: '/urun/cart',
            type: 'GET',
            success: function(res){
                cartCount = res.cart_count || 0;
                $('#cartBadge').text(cartCount);
                console.log('Sepet güncellendi:', cartCount);
            }
        });
    }

    // ------------------------------
    // ADD TO CART - KÜÇÜK KARTLAR
    // ------------------------------
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🛒 Küçük kartta sepete ekle tıklandı');
        
        const $btn = $(this);
        const productId = $btn.data('product-id');
        const quantity = parseInt($(`.quantity-input[data-product-id="${productId}"]`).val()) || 1;
        
        console.log('Product ID:', productId, 'Quantity:', quantity);
        
        if (!productId) {
            console.error('HATA: Product ID bulunamadı!');
            showToast('Ürün ID bulunamadı', 'error');
            return;
        }
        
        addToCart(productId, quantity);
    });

    // ------------------------------
    // ADD TO CART - MODAL
    // ------------------------------
    $(document).on('click', '.add-to-cart-modal', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🛒 Modal sepete ekle tıklandı');
        
        const productId = $(this).data('product-id');
        const quantity = parseInt($('#modal-quantity').val()) || 1;
        
        console.log('Product ID:', productId, 'Quantity:', quantity);
        addToCart(productId, quantity);
    });

    // ------------------------------
    // ADD TO CART FUNCTION
    // ------------------------------
    function addToCart(productId, quantity) {
        console.log('➡️ addToCart çağrıldı:', {productId, quantity});
        
        $.ajax({
            url: '/urun/add-to-cart',
            type: 'POST',
            data: { 
                product_id: productId, 
                quantity: quantity
            },
            beforeSend: function() {
                console.log('📤 AJAX isteği gönderiliyor...');
            },
            success: function(res) {
                console.log('✅ Başarılı yanıt:', res);
                
                if (res.success) {
                    cartCount = res.cart_count;
                    $('#cartBadge').text(cartCount).addClass('bounce');
                    setTimeout(() => $('#cartBadge').removeClass('bounce'), 400);
                    showToast(res.message || 'Sepete eklendi', 'success');
                    $('#productModal').addClass('hidden');
                } else {
                    showToast(res.error || 'Sepete eklenemedi', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX hatası:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                showToast('Sepete eklenirken hata oluştu', 'error');
            }
        });
    }


    // Sepet modalı aç
$(document).on('click', '#cartIcon', function(e) {
    e.preventDefault();
    console.log('Sepet ikonu tıklandı');
    openCartModal();
});

function openCartModal() {
    $('#cartModal').removeClass('hidden');
    loadCartContent();
}

function loadCartContent() {
    $('#cart-content').html('<div class="text-center py-12"><i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i><p class="mt-4 text-gray-600">Yükleniyor...</p></div>');
    
    $.ajax({
        url: '/urun/cart',
        type: 'GET',
        success: function(res) {
            console.log('Sepet içeriği:', res);
            
            if (!res.cart_items || res.cart_items.length === 0) {
                $('#cart-content').html(`
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl text-gray-500">Sepetiniz boş</p>
                    </div>
                `);
                return;
            }
            
            let html = '<div class="space-y-4">';
            res.cart_items.forEach(item => {
                html += `
                    <div class="flex items-center space-x-4 border-b pb-4">
                        <img src="/${item.ProductImage || 'images/no-image.png'}" class="w-20 h-20 object-contain">
                        <div class="flex-1">
                            <h3 class="font-bold">${item.ProductBrand} ${item.ProductModel}</h3>
                            <p class="text-sm text-gray-600">${item.ProductName}</p>
                            <p class="text-red-500 font-bold">${parseFloat(item.UnitPrice).toLocaleString('tr-TR')} ₺ x ${item.Quantity}</p>
                        </div>
                        <button class="remove-cart-item text-red-500 hover:text-red-700" data-cart-id="${item.CartItemId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            
            html += `
                <div class="border-t pt-4">
                    <div class="flex justify-between text-xl font-bold">
                        <span>Toplam:</span>
                        <span class="text-red-500">${parseFloat(res.total_amount).toLocaleString('tr-TR')} ₺</span>
                    </div>
                    <a href="/checkout" class="block w-full bg-green-500 text-white text-center py-3 rounded-lg mt-4 font-bold hover:bg-green-600">
                        Siparişi Tamamla
                    </a>
                </div>
            </div>`;
            
            $('#cart-content').html(html);
        },
        error: function(xhr) {
            console.error('Sepet yükleme hatası:', xhr);
            $('#cart-content').html(`
                <div class="text-center py-12 text-red-500">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p>Sepet yüklenemedi</p>
                </div>
            `);
        }
    });
}

$('#closeCartModal').click(() => $('#cartModal').addClass('hidden'));

// Sepetten ürün silme
$(document).on('click', '.remove-cart-item', function() {
    const cartId = $(this).data('cart-id');
    console.log('Sepetten kaldırılıyor:', cartId);
    
    $.ajax({
        url: '/urun/remove-from-cart',
        type: 'POST',
        data: { cart_item_id: cartId },
        success: function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                updateCartBadge();
                loadCartContent();
            }
        }
    });
});

    // ------------------------------
    // QUANTITY CONTROLS
    // ------------------------------
    $(document).on('click', '.quantity-btn', function(e) {
        e.stopPropagation();
        const productId = $(this).data('product-id');
        const input = $(`.quantity-input[data-product-id="${productId}"]`);
        let val = parseInt(input.val()) || 1;
        input.val($(this).data('action') === 'plus' ? val + 1 : Math.max(1, val - 1));
    });

    $(document).on('click', '.modal-quantity-btn', function() {
        const input = $('#modal-quantity');
        let val = parseInt(input.val()) || 1;
        input.val($(this).data('action') === 'plus' ? val + 1 : Math.max(1, val - 1));
    });

    // ------------------------------
    // PRODUCT MODAL
    // ------------------------------
    $(document).on('click', '.product-card', function(e) {
        if ($(e.target).closest('.quantity-btn, .add-to-cart-btn, button').length) {
            return;
        }
        loadProductDetails($(this).data('product-id'));
    });

    function loadProductDetails(productId) {
        $('#productModal').removeClass('hidden');
        $('#product-details-content').html('<div class="text-center py-12"><i class="fas fa-spinner fa-spin text-4xl text-red-500"></i><p class="mt-4 text-gray-600">Yükleniyor...</p></div>');

        $.ajax({
            url: `/urun/detay/${productId}`,
            type: 'GET',
            success: function(response) {
                const product = response.product || response;
                const imageUrl = product.Resim ? '/' + product.Resim : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHJlY3Qgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNlZWUiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1zaXplPSIxOCI+UmVzaW0gWW9rPC90ZXh0Pjwvc3ZnPg==';
                const price = parseFloat(product.Fiyat || 0).toLocaleString('tr-TR');

                const html = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div><img src="${imageUrl}" alt="${product.Ad}" class="w-full h-96 object-contain border rounded-xl p-4 bg-gray-50"></div>
                        <div class="space-y-6">
                            <div>
                                <span class="inline-block bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full mb-2">YENİ</span>
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">${product.Marka || ''} ${product.Model || ''}</h2>
                                <p class="text-gray-600">${product.Ad || ''}</p>
                            </div>
                            <div class="border-t border-b py-4">
                                <div class="text-4xl font-bold text-red-500 mb-2">${price} ₺</div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="font-semibold text-gray-700">Adet:</span>
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="modal-quantity-btn px-4 py-2 hover:bg-gray-100" data-action="minus"><i class="fas fa-minus"></i></button>
                                    <input type="number" id="modal-quantity" class="w-16 text-center border-0 bg-transparent" value="1" min="1">
                                    <button class="modal-quantity-btn px-4 py-2 hover:bg-gray-100" data-action="plus"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <button class="w-full btn-primary text-white py-4 rounded-xl font-bold text-lg add-to-cart-modal" data-product-id="${product.ProductId}">
                                <i class="fas fa-shopping-cart mr-2"></i>Sepete Ekle
                            </button>
                        </div>
                    </div>
                `;
                $('#product-details-content').html(html);
            }
        });
    }

    $('#closeProductModal').click(() => $('#productModal').addClass('hidden'));

    // ------------------------------
    // TOAST
    // ------------------------------
    function showToast(msg, type='info') {
        const colors = {success:'bg-green-500', error:'bg-red-500', warning:'bg-yellow-500', info:'bg-blue-500'};
        const icons = {success:'fa-check-circle', error:'fa-exclamation-circle', warning:'fa-exclamation-triangle', info:'fa-info-circle'};
        const toast = $(`<div class="fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3">
            <i class="fas ${icons[type]}"></i><span>${msg}</span></div>`);
        $('body').append(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // İlk yüklemede sepeti güncelle
    updateCartBadge();
    
    console.log('✅ Tüm event listener\'lar kuruldu');
});
</script>
@endpush


