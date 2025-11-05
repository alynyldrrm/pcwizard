<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Toplama Sihirbazı</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .product-incompatible {
            opacity: 0.4;
            pointer-events: none;
        }
        .selected-product {
            border: 2px solid #059669;
            background-color: #ecfdf5;
        }
        .loading {
            display: none;
        }
        .category-active {
            border: 2px solid #3B82F6;
            background-color: #EBF4FF;
        }
        .cart-badge {
            animation: bounce 0.5s;
        }
        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-bg shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🚀</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white">PC Toplama Sihirbazı</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Konfigürasyonlar Butonu -->
                    <button id="configurationsBtn" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Konfigürasyonlar
                    </button>
                    
                    <!-- Sepet Butonu -->
                    <button id="cartBtn" class="relative glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 21v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                        </svg>
                        Sepet
                        <span id="cartBadge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">{{ $cartCount ?? 0 }}</span>
                    </button>
                    
                    <a href="{{ route('dashboard') }}" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Geri Dön
                    </a>
                    
                    <button id="clearAll" class="bg-red-500 bg-opacity-90 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hepsini Temizle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Ana İçerik -->
            <div class="lg:w-3/4">
                <!-- Kategoriler -->
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold">📦</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Kategoriler</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                        <div class="category-card cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-4 hover:shadow-xl hover:scale-105 transition-all duration-300" 
                             data-category-id="{{ $category->CategoryId }}">
                            <div class="text-center">
                                @if($category->CategoryImage)
                                    <img src="{{ asset($category->CategoryImage) }}" 
                                         alt="{{ $category->CategoryName }}" 
                                         class="w-16 h-16 mx-auto mb-3 object-cover rounded-xl">
                                @else
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center">
                                        <span class="text-2xl">🖥️</span>
                                    </div>
                                @endif
                                <h3 class="font-semibold text-gray-800">{{ $category->CategoryName }}</h3>
                                
                                <!-- Seçili ürün gösterimi -->
                                <div class="selected-product-display mt-2" id="selected-{{ $category->CategoryId }}" style="display: none;">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-2">
                                        <img class="w-8 h-8 mx-auto mb-1 object-cover rounded selected-img" src="" alt="">
                                        <p class="text-xs font-medium selected-name"></p>
                                        <p class="text-xs text-green-600 selected-price"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ürünler -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100" id="products-section" style="display: none;">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800" id="products-title">Ürünler</h2>
                        <button id="closeProducts" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-2 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="loading text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600"></div>
                        <p class="mt-4 text-gray-600">Ürünler yükleniyor...</p>
                    </div>
                    
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Ürünler buraya yüklenecek -->
                    </div>
                </div>
            </div>

            <!-- Seçilen Ürünler Paneli -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-4 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-2">
                            <span class="text-white text-sm">✓</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Seçilen Ürünler</h3>
                    </div>
                    
                    <div id="selected-products-list" class="space-y-3 mb-6">
                        <!-- Seçilen ürünler buraya gelecek -->
                    </div>
                    
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span class="text-gray-800">Toplam:</span>
                            <span id="total-price" class="text-green-600 bg-green-50 px-3 py-1 rounded-lg">0 ₺</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <button id="addAllToCart" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-xl hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-500 transition-all duration-200 shadow-lg font-semibold" disabled>
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 21v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                            </svg>
                            Tümünü Sepete Ekle
                        </button>
                        
                        <button id="saveConfiguration" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 transition-all duration-200 shadow-lg font-semibold" disabled>
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Konfigürasyonu Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sepet Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 21v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                        </svg>
                        <h2 class="text-2xl font-bold">Sepetim</h2>
                    </div>
                    <button class="close-modal text-white hover:text-gray-300 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="cart-items" class="space-y-4 max-h-96 overflow-y-auto">
                    <!-- Sepet öğeleri buraya gelecek -->
                </div>
                
                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-between items-center text-xl font-bold mb-4">
                        <span>Toplam:</span>
                        <span id="cart-total" class="text-green-600">0 ₺</span>
                    </div>
            
                <button onclick="completeOrder()" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 rounded-xl hover:from-orange-600 hover:to-red-700 transition-all duration-200 shadow-lg font-semibold">
                            Siparişi Tamamla
                </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Konfigürasyonlar Modal -->
    <div id="configurationsModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h2 class="text-2xl font-bold">Kayıtlı Konfigürasyonlar</h2>
                    </div>
                    <button class="close-modal text-white hover:text-gray-300 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="configurations-list" class="space-y-4 max-h-96 overflow-y-auto">
                    <!-- Konfigürasyonlar buraya gelecek -->
                </div>
            </div>
        </div>
    </div>

    <!-- Konfigürasyon Kaydet Modal -->
    <div id="saveConfigModal" class="modal">
        <div class="modal-content max-w-md">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-xl">
                <h2 class="text-xl font-bold">Konfigürasyon Kaydet</h2>
            </div>
            
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Konfigürasyon Adı:</label>
                <input type="text" id="configName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Örn: Gaming PC 2024">
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button class="close-modal px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">İptal</button>
                    <button id="confirmSaveConfig" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // GLOBAL FONKSİYON - $(document).ready() DI ⁇INDA:
        function completeOrder() {
            if (confirm('Siparişi tamamlamak istediğinizden emin misiniz?')) {
                $.post('/wizard/complete-order', function(response) {
                    if (response.success) {
                        $('#cartModal').fadeOut(300);
                        showToast('Sipariş oluşturuldu, ödeme sayfasına yönlendiriliyorsunuz...', 'success');
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1500);
                    } else {
                        showToast(response.error || 'Sipariş oluşturulamadı!', 'error');
                    }
                }).fail(function(xhr) {
                    let errorMsg = 'Bir hata oluştu!';
                    if (xhr.status === 401) {
                        errorMsg = 'Sipariş vermek için giriş yapmalısınız!';
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    showToast(errorMsg, 'error');
                });
            }
        }

        $(document).ready(function() {
            var selectedProducts = @json($selectedProducts ?? []);
            var currentCategory = null;
            var cartCount = {{ $cartCount ?? 0 }};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            updateSelectedProducts();
            updateCategoryDisplays();

            // Kategori seçimi
            $('.category-card').click(function() {
                currentCategory = $(this).data('category-id');
                $('.category-card').removeClass('category-active');
                $(this).addClass('category-active');
                loadProducts(currentCategory);
            });

            // Ürünleri yükle
            // Ürünleri yükle
function loadProducts(categoryId) {
    $('#products-section').show();
    $('.loading').show();
    $('#products-grid').empty();

    $.get('/wizard/products/' + categoryId, function(response) {
        $('.loading').hide();
        $('#products-title').text(response.category.CategoryName + ' Ürünleri');
        
        var productsHtml = '';
        response.products.forEach(function(product) {
            var compatibleClass = product.is_compatible ? '' : 'product-incompatible';
            var compatibleBadge = product.is_compatible ? '' : '<div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-semibold">Uyumsuz</div>';
            var imageUrl = product.Resim ? '/' + product.Resim : '/images/products/default.jpg';
            var price = parseFloat(product.Fiyat).toLocaleString('tr-TR');
            var stock = product.Stok || 0;
            
            // Konfigürasyon butonu için uyumluluk kontrolü
            var configButtonClass = product.is_compatible ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed';
            var configButtonDisabled = product.is_compatible ? '' : 'disabled';
            
            productsHtml += '<div class="product-card bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-xl transition-all duration-300 relative" data-product-id="' + product.ProductId + '">' +
                compatibleBadge +
                '<img src="' + imageUrl + '" alt="' + product.Ad + '" class="w-full h-40 object-cover rounded-xl mb-3" onerror="this.src=\'/images/products/default.jpg\';">' +
                '<h4 class="font-bold text-sm mb-1 text-gray-800">' + product.Marka + ' ' + product.Model + '</h4>' +
                '<p class="text-xs text-gray-600 mb-1 line-clamp-2">' + product.Ad + '</p>' +
                '<p class="text-sm text-gray-500 mb-2">Stok: ' + stock + ' adet</p>' +
                '<p class="text-lg font-bold text-green-600 mb-3">' + price + ' ₺</p>' +
                '<div class="space-y-2">' +
                    '<button class="w-full ' + configButtonClass + ' text-white py-2 rounded-xl text-sm font-semibold transition-all duration-200 select-btn" ' + configButtonDisabled + '>' +
                        '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' +
                        'Konfigürasyona Seç' +
                    '</button>' +
                    '<button class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 text-sm font-semibold transition-all duration-200 add-to-cart-btn">' +
                        '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9M17 21v-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path></svg>' +
                        'Sepete Ekle' +
                    '</button>' +
                '</div>' +
            '</div>';
        });
        
        $('#products-grid').html(productsHtml);
    });
}

            // Konfigürasyona seç
            $(document).on('click', '.select-btn', function(e) {
                e.stopPropagation();
                var productId = $(this).closest('.product-card').data('product-id');
                selectProduct(productId);
            });

            // Sepete ekle (tekli)
            $(document).on('click', '.add-to-cart-btn', function(e) {
                e.stopPropagation();
                var productId = $(this).closest('.product-card').data('product-id');
                addToCart(productId);
            });

            function selectProduct(productId) {
                $.post('/wizard/select', { product_id: productId }, function(response) {
                    if (response.success) {
                        selectedProducts = response.selected_products;
                        updateSelectedProducts();
                        updateCategoryDisplays();
                        
                        if (currentCategory) loadProducts(currentCategory);
                        
                        // Success toast
                        showToast('Ürün konfigürasyona eklendi!', 'success');
                    }
                });
            }

        function addToCart(productId) {
    $.post({
        url: '/wizard/add-to-cart',
        data: {
            product_id: productId,   // ürün id
            _token: '{{ csrf_token() }}' // CSRF token
        },
        success: function(response) {
            if (response.success) {
                $('#cartBadge').text(response.cart_count).addClass('cart-badge');
                showToast(response.message, 'success');
                setTimeout(function() {
                    $('#cartBadge').removeClass('cart-badge');
                }, 500);
            }
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                showToast('Sepete ürün eklemek için giriş yapmalısınız!', 'error');
            } else {
                showToast(xhr.responseJSON.error, 'error'); // hata mesajını göster
            }
        }
    });
}

            function updateSelectedProducts() {
                var html = '';
                var total = 0;
                
                if (selectedProducts.length === 0) {
                    html = '<div class="text-center py-8 text-gray-500">' +
                        '<div class="text-4xl mb-2">📦</div>' +
                        '<p>Henüz ürün seçilmedi</p>' +
                        '<p class="text-sm">Kategori seçerek başlayın!</p>' +
                        '</div>';
                } else {
                    selectedProducts.forEach(function(product) {
                        total += parseFloat(product.Fiyat);
                        var imageUrl = product.Resim ? '/' + product.Resim : '/images/products/default.jpg';
                        var price = parseFloat(product.Fiyat).toLocaleString('tr-TR');
                        
                        html += '<div class="bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-xl border border-gray-200">' +
                            '<div class="flex items-center space-x-3">' +
                                '<img src="' + imageUrl + '" alt="' + product.Ad + '" class="w-12 h-12 object-cover rounded-lg" onerror="this.src=\'/images/products/default.jpg\';">' +
                                '<div class="flex-1 min-w-0">' +
                                    '<p class="text-sm font-bold truncate text-gray-800">' + product.Marka + ' ' + product.Model + '</p>' +
                                    '<p class="text-xs text-gray-600 truncate">' + product.Ad + '</p>' +
                                    '<p class="text-sm font-bold text-green-600">' + price + ' ₺</p>' +
                                '</div>' +
                                '<button class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-2 transition-all duration-200 remove-product" data-category-id="' + product.CategoryId + '">' +
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' +
                                    '</svg>' +
                                '</button>' +
                            '</div>' +
                        '</div>';
                    });
                }
                
                $('#selected-products-list').html(html);
                $('#total-price').text(total.toLocaleString('tr-TR') + ' ₺');
                $('#saveConfiguration, #addAllToCart').prop('disabled', selectedProducts.length === 0);
            }

            function updateCategoryDisplays() {
                $('.selected-product-display').hide();
                
                selectedProducts.forEach(function(product) {
                    var display = $('#selected-' + product.CategoryId);
                    display.show();
                    var imageUrl = product.Resim ? '/' + product.Resim : '/images/products/default.jpg';
                    display.find('.selected-img').attr('src', imageUrl);
                    display.find('.selected-name').text(product.Marka + ' ' + product.Model);
                    display.find('.selected-price').text(parseFloat(product.Fiyat).toLocaleString('tr-TR') + ' ₺');
                });
            }

            // Ürün kaldır
            $(document).on('click', '.remove-product', function() {
                var categoryId = $(this).data('category-id');
                
                $.post('/wizard/remove', { category_id: categoryId }, function(response) {
                    if (response.success) {
                        selectedProducts = response.selected_products;
                        updateSelectedProducts();
                        updateCategoryDisplays();
                        if (currentCategory) loadProducts(currentCategory);
                        showToast('Ürün konfigürasyondan kaldırıldı', 'info');
                    }
                });
            });

            // Tümünü sepete ekle
            $('#addAllToCart').click(function() {
                $.post('/wizard/add-all-to-cart', function(response) {
                    if (response.success) {
                        cartCount = response.cart_count;
                        $('#cartBadge').text(cartCount).addClass('cart-badge');
                        showToast(response.message, 'success');
                        
                        setTimeout(function() {
                            $('#cartBadge').removeClass('cart-badge');
                        }, 500);
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 401) {
                        showToast('Sepete ürün eklemek için giriş yapmalısınız!', 'error');
                    }
                });
            });

            // Hepsini temizle
            $('#clearAll').click(function() {
                if (confirm('Tüm seçimleri temizlemek istediğinizden emin misiniz?')) {
                    $.post('/wizard/clear', function(response) {
                        if (response.success) {
                            selectedProducts = [];
                            updateSelectedProducts();
                            updateCategoryDisplays();
                            if (currentCategory) loadProducts(currentCategory);
                            showToast('Tüm seçimler temizlendi', 'info');
                        }
                    });
                }
            });

            // Ürünler panelini kapat
            $('#closeProducts').click(function() {
                $('#products-section').hide();
                $('.category-card').removeClass('category-active');
                currentCategory = null;
            });

            // Konfigürasyon kaydet modal
            $('#saveConfiguration').click(function() {
                $('#saveConfigModal').fadeIn(300);
            });

            $('#confirmSaveConfig').click(function() {
                var configName = $('#configName').val().trim();
                
                if (!configName) {
                    showToast('Lütfen konfigürasyon adı girin!', 'error');
                    return;
                }

                $.post('/wizard/save-configuration', { config_name: configName }, function(response) {
                    if (response.success) {
                        $('#saveConfigModal').fadeOut(300);
                        $('#configName').val('');
                        showToast(response.message, 'success');
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 401) {
                        showToast('Konfigürasyon kaydetmek için giriş yapmalısınız!', 'error');
                    } else {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Bir hata oluştu';
                        showToast(errorMsg, 'error');
                    }
                });
            });

            // Sepet modal
            $('#cartBtn').click(function() {
                loadCart();
                $('#cartModal').fadeIn(300);
            });

            function loadCart() {
                $.get('/wizard/cart', function(response) {
                    var html = '';
                    
                    if (response.cart_items.length === 0) {
                        html = '<div class="text-center py-8 text-gray-500">' +
                            '<div class="text-4xl mb-2">🛒</div>' +
                            '<p>Sepetiniz boş</p>' +
                            '</div>';
                    } else {
                        response.cart_items.forEach(function(item) {
                            var imageUrl = item.ProductImage ? '/' + item.ProductImage : '/images/products/default.jpg';
                            var price = parseFloat(item.UnitPrice).toLocaleString('tr-TR');
                            
                            html += '<div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">' +
                                '<img src="' + imageUrl + '" alt="' + item.ProductName + '" class="w-16 h-16 object-cover rounded-lg" onerror="this.src=\'/images/products/default.jpg\';">' +
                                '<div class="flex-1">' +
                                    '<h4 class="font-semibold text-gray-800">' + item.ProductBrand + ' ' + item.ProductModel + '</h4>' +
                                    '<p class="text-sm text-gray-600">' + item.ProductName + '</p>' +
                                    '<div class="flex items-center justify-between mt-2">' +
                                        '<span class="text-lg font-bold text-green-600">' + price + ' ₺</span>' +
                                        '<div class="flex items-center space-x-2">' +
                                            '<span class="text-sm text-gray-600">Adet: ' + item.Quantity + '</span>' +
                                            '<button class="text-red-500 hover:text-red-700 remove-from-cart" data-cart-item-id="' + item.CartItemId + '">' +
                                                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' +
                                                '</svg>' +
                                            '</button>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                        });
                    }
                    
                    $('#cart-items').html(html);
                    var totalAmount = response.total_amount || 0;
                    $('#cart-total').text(parseFloat(totalAmount).toLocaleString('tr-TR') + ' ₺');
                }).fail(function(xhr) {
                    if (xhr.status === 401) {
                        $('#cart-items').html('<div class="text-center py-8 text-gray-500"><p>Sepeti görüntülemek için giriş yapmalısınız</p></div>');
                        $('#cart-total').text('0 ₺');
                    }
                });
            }

            // Sepetten kaldır
            $(document).on('click', '.remove-from-cart', function() {
                var cartItemId = $(this).data('cart-item-id');
                
                $.post('/wizard/remove-from-cart', { cart_item_id: cartItemId }, function(response) {
                    if (response.success) {
                        cartCount = response.cart_count;
                        $('#cartBadge').text(cartCount);
                        loadCart(); // Sepeti yenile
                        showToast(response.message, 'success');
                    }
                });
            });

            // Konfigürasyonlar modal
            $('#configurationsBtn').click(function() {
                loadConfigurations();
                $('#configurationsModal').fadeIn(300);
            });

            function loadConfigurations() {
                $.get('/wizard/configurations', function(response) {
                    var html = '';
                    
                    if (response.configurations.length === 0) {
                        html = '<div class="text-center py-8 text-gray-500">' +
                            '<div class="text-4xl mb-2">⚙️</div>' +
                            '<p>Kayıtlı konfigürasyon bulunmuyor</p>' +
                            '</div>';
                    } else {
                        response.configurations.forEach(function(config) {
                            var configName = config.Name || 'Konfigürasyon';
                            var totalAmount = parseFloat(config.TotalPrice).toLocaleString('tr-TR');
                            
                            var createdDate = 'Tarih Yok';
                            if (config.CreatedDate) {
                                createdDate = new Date(config.CreatedDate).toLocaleDateString('tr-TR');
                            }

                            var itemCount = config.items ? config.items.length : 0;
                            
                            html += '<div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-xl border border-blue-200">' +
                                '<div class="flex items-center justify-between mb-3">' +
                                    '<h4 class="font-bold text-lg text-gray-800">' + configName + '</h4>' +
                                    '<div class="flex space-x-2">' +
                                        '<button class="text-blue-600 hover:text-blue-800 load-config" data-config-id="' + config.BuildId + '" title="Yükle">' +
                                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>' +
                                            '</svg>' +
                                        '</button>' +
                                        '<button class="text-red-600 hover:text-red-800 delete-config" data-config-id="' + config.BuildId + '" title="Sil">' +
                                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' +
                                            '</svg>' +
                                        '</button>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="text-sm text-gray-600 mb-2">' +
                                    itemCount + ' ürün • ' + totalAmount + ' ₺' +
                                '</div>' +
                                '<div class="text-xs text-gray-500">' + createdDate + '</div>' +
                            '</div>';
                        });
                    }
                    $('#configurations-list').html(html);
                }).fail(function(xhr) {
                    if (xhr.status === 401) {
                        $('#configurations-list').html('<div class="text-center py-8 text-gray-500"><p>Konfigürasyonları görüntülemek için giriş yapmalısınız</p></div>');
                    }
                });
            }

            // Konfigürasyon yükle ve summary sayfasına yönlendir
            $(document).on('click', '.load-config', function() {
                var configId = $(this).data('config-id');
                
                $.post('/wizard/load-configuration', { config_id: configId }, function(response) {
                    if (response.success) {
                        selectedProducts = response.selected_products;
                        updateSelectedProducts();
                        updateCategoryDisplays();
                        $('#configurationsModal').fadeOut(300);
                        showToast('"' + response.config_name + '" konfigürasyonu yüklendi!', 'success');

                        // Burada configId ile birlikte summary sayfasına yönlendiriyoruz
                        window.location.href = '/summary/' + configId;
                    }
                });
            });

            // Konfigürasyon sil
            $(document).on('click', '.delete-config', function() {
                if (!confirm('Bu konfigürasyonu silmek istediğinizden emin misiniz?')) return;
                
                var configId = $(this).data('config-id');
                
                $.post('/wizard/delete-configuration', { config_id: configId }, function(response) {
                    if (response.success) {
                        loadConfigurations(); // Listeyi yenile
                        showToast(response.message, 'success');
                    }
                });
            });

            // Modal kapatma
            $('.close-modal').click(function() {
                $(this).closest('.modal').fadeOut(300);
            });

            $(document).on('click', '.modal', function(e) {
                if (e.target === this) {
                    $(this).fadeOut(300);
                }
            });

            // ESC tuşu ile modal kapatma
            $(document).keydown(function(e) {
                if (e.key === 'Escape') {
                    $('.modal').fadeOut(300);
                }
            });

            // Toast mesajları - GLOBAL SCOPE'da OLMALI showToast FUNKS ⁇YONU İÇİN
            window.showToast = function(message, type) {
                type = type || 'info';
                var bgColor = type === 'success' ? 'bg-green-500' : 
                             type === 'error' ? 'bg-red-500' : 
                             type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
                
                var icon = type === 'success' ? '✓' : 
                          type === 'error' ? '✗' : 
                          type === 'warning' ? '⚠' : 'ℹ';

                var toast = $('<div class="fixed top-4 right-4 ' + bgColor + ' text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center space-x-2 transform translate-x-full opacity-0 transition-all duration-300">' +
                    '<span class="text-lg">' + icon + '</span>' +
                    '<span>' + message + '</span>' +
                '</div>');

                $('body').append(toast);

                setTimeout(function() {
                    toast.removeClass('translate-x-full opacity-0');
                }, 100);

                setTimeout(function() {
                    toast.addClass('translate-x-full opacity-0');
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                }, 3000);
            }
        });
    </script>

         
</body>
</html>