<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->Marka }} {{ $product->Model }} - Avantaj Bilişim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .product-image { transition: all 0.3s ease; }
        .product-image:hover { transform: scale(1.05); }
        .btn-primary { background: linear-gradient(135deg, #ef4444, #dc2626); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(239, 68, 68, 0.3); }
        .bounce { animation: bounce 0.5s; }
        @keyframes bounce { 0%,20%,60%,100%{transform:translateY(0);}40%{transform:translateY(-10px);}80%{transform:translateY(-5px);} }
        .similar-product-card { transition: all 0.3s ease; border: 1px solid #e5e7eb; }
        .similar-product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: #ef4444; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header (aynı header'ı kullanın) -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-teal-400 rounded-xl flex items-center justify-center">
                        <i class="fas fa-rocket text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">AVANTAJ BİLİŞİM</h1>
                        <p class="text-xs text-gray-500">Türkiye'nin Avantajı Var</p>
                    </div>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-gray-700 hover:text-red-500 font-medium transition-colors">Ana Sayfa</a>
                    <a href="/urunler" class="text-gray-700 hover:text-red-500 font-medium transition-colors">Ürünler</a>
                    <a href="/#categories" class="text-gray-700 hover:text-red-500 font-medium transition-colors">Kategoriler</a>
                    <a href="/#contact" class="text-gray-700 hover:text-red-500 font-medium transition-colors">İletişim</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button id="cartBtn" class="relative bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all">
                        <i class="fas fa-shopping-cart mr-2"></i>Sepet
                        <span id="cartBadge" class="absolute -top-2 -right-2 bg-yellow-500 text-xs rounded-full w-6 h-6 flex items-center justify-center text-black font-bold">0</span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 font-medium">Giriş</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all">Kayıt</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <div class="flex items-center mb-6">
            <a href="/" class="text-gray-500 hover:text-red-500 mr-2"><i class="fas fa-home"></i></a>
            <span class="text-gray-400 mr-2">></span>
            <a href="/urunler" class="text-gray-500 hover:text-red-500 mr-2">Ürünler</a>
            <span class="text-gray-400 mr-2">></span>
            @if($product->category)
                <a href="/urunler?category={{ $product->category->CategoryId }}" class="text-gray-500 hover:text-red-500 mr-2">{{ $product->category->CategoryName }}</a>
                <span class="text-gray-400 mr-2">></span>
            @endif
            <span class="text-gray-700 font-medium">{{ $product->Marka }} {{ $product->Model }}</span>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <div class="bg-white rounded-2xl p-8 border-2 border-gray-100">
                    <img id="mainImage" src="{{ asset($product->Resim ?? 'images/no-image.png') }}" alt="{{ $product->Ad }}" class="w-full h-96 object-contain rounded-lg product-image">
                </div>
                
                <!-- Thumbnail Images -->
                <div class="grid grid-cols-4 gap-3">
                    <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" class="thumbnail-img w-full h-24 object-contain bg-gray-50 rounded-lg border-2 border-red-500 cursor-pointer">
                    <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" class="thumbnail-img w-full h-24 object-contain bg-gray-50 rounded-lg border cursor-pointer hover:border-red-500">
                    <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" class="thumbnail-img w-full h-24 object-contain bg-gray-50 rounded-lg border cursor-pointer hover:border-red-500">
                    <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" class="thumbnail-img w-full h-24 object-contain bg-gray-50 rounded-lg border cursor-pointer hover:border-red-500">
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-bold">YENİ</span>
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-bold">STOKTA</span>
                        @if($product->category)
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">{{ $product->category->CategoryName }}</span>
                        @endif
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->Marka }} {{ $product->Model }}</h1>
                    <p class="text-gray-600 text-xl mb-4">{{ $product->Ad }}</p>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex text-yellow-400 text-lg">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        </div>
                        <span class="text-gray-500">(24 değerlendirme)</span>
                        <span class="text-gray-400">•</span>
                        <span class="text-green-600 font-semibold">127 satıldı</span>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                    <div class="flex items-baseline gap-4 mb-2">
                        <div class="text-4xl font-bold text-red-600">{{ number_format($product->Fiyat, 2, ',', '.') }} ₺</div>
                        <div class="text-lg text-gray-500 line-through">{{ number_format($product->Fiyat * 1.2, 2, ',', '.') }} ₺</div>
                        <div class="bg-green-500 text-white px-2 py-1 rounded-full text-sm font-bold">%17 İndirim</div>
                    </div>
                    <p class="text-sm text-gray-600">KDV Dahil • Ücretsiz Kargo • 24 Aya Varan Taksit</p>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-6">
                        <label class="font-semibold text-gray-700 text-lg">Adet:</label>
                        <div class="flex items-center border-2 border-gray-300 rounded-xl">
                            <button class="quantity-btn px-4 py-3 hover:bg-gray-100 text-lg" data-action="minus" data-product-id="{{ $product->ProductId }}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="quantity-input w-20 text-center border-0 bg-transparent text-lg font-semibold" value="1" min="1" data-product-id="{{ $product->ProductId }}">
                            <button class="quantity-btn px-4 py-3 hover:bg-gray-100 text-lg" data-action="plus" data-product-id="{{ $product->ProductId }}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <button class="add-to-cart-btn w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 rounded-xl font-bold text-xl hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all shadow-lg" data-product-id="{{ $product->ProductId }}">
                        <i class="fas fa-shopping-cart mr-3"></i>Sepete Ekle
                    </button>

                    <div class="grid grid-cols-2 gap-4">
                        <button class="wishlist-btn flex items-center justify-center gap-2 bg-blue-500 text-white py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors" data-product-id="{{ $product->ProductId }}">
                            <i class="far fa-heart"></i>Favorilere Ekle
                        </button>
                        <button class="flex items-center justify-center gap-2 bg-gray-500 text-white py-3 rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                            <i class="fas fa-share"></i>Paylaş
                        </button>
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="font-bold text-gray-800 mb-4 text-lg">Avantajlar</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-truck text-green-500 text-lg"></i>
                            <span class="text-gray-700">Ücretsiz Kargo</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shield-alt text-blue-500 text-lg"></i>
                            <span class="text-gray-700">2 Yıl Garanti</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-undo text-orange-500 text-lg"></i>
                            <span class="text-gray-700">14 Gün İade</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-headset text-purple-500 text-lg"></i>
                            <span class="text-gray-700">7/24 Destek</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-12">
            <div class="border-b">
                <nav class="flex">
                    <button class="tab-btn px-8 py-4 font-semibold text-red-500 border-b-2 border-red-500 bg-red-50" data-tab="description">Ürün Açıklaması</button>
                    <button class="tab-btn px-8 py-4 font-semibold text-gray-600 hover:text-red-500" data-tab="specifications">Teknik Özellikler</button>
                    <button class="tab-btn px-8 py-4 font-semibold text-gray-600 hover:text-red-500" data-tab="reviews">Değerlendirmeler</button>
                    <button class="tab-btn px-8 py-4 font-semibold text-gray-600 hover:text-red-500" data-tab="qa">Soru & Cevap</button>
                </nav>
            </div>

            <div class="p-8">
                <!-- Description Tab -->
                <div id="description" class="tab-content">
                    @if($product->Aciklama)
                        <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
                            {!! nl2br(e($product->Aciklama)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Bu ürün için henüz açıklama eklenmemiş.</p>
                    @endif
                </div>

                <!-- Specifications Tab -->
                <div id="specifications" class="tab-content hidden">
                    @if($product->criterias->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach($product->criterias as $criteria)
                                @if($criteria->kriter && $criteria->CriteriaValue)
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">{{ $criteria->kriter->KriterAdi }}:</span>
                                        <span class="text-gray-900 font-semibold">{{ $criteria->CriteriaValue }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Bu ürün için teknik özellik bilgisi bulunmuyor.</p>
                    @endif
                </div>

                <!-- Reviews Tab -->
                <div id="reviews" class="tab-content hidden">
                    <div class="text-center py-12">
                        <i class="fas fa-star text-4xl text-yellow-400 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz değerlendirme yok</h3>
                        <p class="text-gray-600 mb-6">Bu ürün için ilk değerlendirmeyi siz yapın!</p>
                        <button class="btn-primary text-white px-6 py-3 rounded-lg">Değerlendirme Yap</button>
                    </div>
                </div>

                <!-- Q&A Tab -->
                <div id="qa" class="tab-content hidden">
                    <div class="text-center py-12">
                        <i class="fas fa-question-circle text-4xl text-blue-400 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Henüz soru sorulmamış</h3>
                        <p class="text-gray-600 mb-6">Bu ürün hakkında merak ettiğiniz her şeyi sorabilirsiniz!</p>
                        <button class="btn-primary text-white px-6 py-3 rounded-lg">Soru Sor</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Products -->
        @if($similarProducts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-8 text-gray-800">
                    <i class="fas fa-heart mr-3 text-red-500"></i>Benzer Ürünler
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarProducts as $similar)
                        <div class="similar-product-card bg-white rounded-2xl overflow-hidden shadow-lg cursor-pointer" onclick="window.location.href='/urun/{{ $similar->ProductId }}'">
                            <div class="relative">
                                <img src="{{ asset($similar->Resim ?? 'images/no-image.png') }}" alt="{{ $similar->Ad }}" class="w-full h-48 object-contain p-4">
                                <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">Benzer</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ $similar->Marka }} {{ $similar->Model }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $similar->Ad }}</p>
                                <div class="text-xl font-bold text-red-500">{{ number_format($similar->Fiyat, 2, ',', '.') }} ₺</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
    $(function() {
        // Tab switching
        $('.tab-btn').click(function() {
            const tabId = $(this).data('tab');
            
            // Update tab buttons
            $('.tab-btn').removeClass('text-red-500 border-b-2 border-red-500 bg-red-50').addClass('text-gray-600');
            $(this).removeClass('text-gray-600').addClass('text-red-500 border-b-2 border-red-500 bg-red-50');
            
            // Update tab content
            $('.tab-content').addClass('hidden');
            $(`#${tabId}`).removeClass('hidden');
        });

        // Thumbnail image switching
        $('.thumbnail-img').click(function() {
            const newSrc = $(this).attr('src');
            $('#mainImage').attr('src', newSrc);
            
            // Update active thumbnail
            $('.thumbnail-img').removeClass('border-red-500').addClass('border-gray-300');
            $(this).removeClass('border-gray-300').addClass('border-red-500');
        });

        // Quantity controls
        $('.quantity-btn').click(function() {
            const action = $(this).data('action');
            const productId = $(this).data('product-id');
            const input = $(`.quantity-input[data-product-id="${productId}"]`);
            let currentValue = parseInt(input.val()) || 1;
            
            if (action === 'plus') {
                input.val(currentValue + 1);
            } else if (action === 'minus' && currentValue > 1) {
                input.val(currentValue - 1);
            }
        });

        // Add to cart
        $('.add-to-cart-btn').click(function() {
            const productId = $(this).data('product-id');
            const quantity = parseInt($(`.quantity-input[data-product-id="${productId}"]`).val()) || 1;
            const btn = $(this);
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-3"></i>Ekleniyor...');
            
            $.post('/urun/add-to-cart', {
                product_id: productId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                if (response.success) {
                    $('#cartBadge').text(response.cart_count || 0).addClass('bounce');
                    showToast('Ürün sepete eklendi!', 'success');
                    setTimeout(() => $('#cartBadge').removeClass('bounce'), 500);
                } else {
                    showToast(response.error || 'Sepete eklenemedi', 'error');
                }
            }).fail(function() {
                showToast('Bir hata oluştu', 'error');
            }).always(function() {
                btn.prop('disabled', false).html('<i class="fas fa-shopping-cart mr-3"></i>Sepete Ekle');
            });
        });

        // Wishlist toggle
        $('.wishlist-btn').click(function() {
            const productId = $(this).data('product-id');
            const btn = $(this);
            
            $.post('/urun/toggle-wishlist', {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                if (response.success) {
                    if (response.action === 'added') {
                        btn.html('<i class="fas fa-heart"></i>Favorilerde').removeClass('bg-blue-500 hover:bg-blue-600').addClass('bg-red-500 hover:bg-red-600');
                        showToast('Favorilere eklendi!', 'success');
                    } else {
                        btn.html('<i class="far fa-heart"></i>Favorilere Ekle').removeClass('bg-red-500 hover:bg-red-600').addClass('bg-blue-500 hover:bg-blue-600');
                        showToast('Favorilerden kaldırıldı!', 'success');
                    }
                } else {
                    showToast(response.error || 'İşlem başarısız', 'error');
                }
            }).fail(function() {
                showToast('Giriş yapmalısınız', 'warning');
            });
        });

        // Toast function
        function showToast(message, type = 'info') {
            const colors = {
                success: 'from-green-500 to-emerald-600',
                error: 'from-red-500 to-red-600',
                warning: 'from-yellow-500 to-orange-600',
                info: 'from-blue-500 to-blue-600'
            };
            
            const toast = $(`
                <div class="fixed top-6 right-6 bg-gradient-to-r ${colors[type]} text-white px-6 py-3 rounded-xl shadow-2xl z-50 transform translate-x-full">
                    ${message}
                </div>
            `);
            
            $('body').append(toast);
            
            // Animate in
            setTimeout(() => {
                toast.removeClass('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                toast.addClass('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Load initial cart count
        @auth
            $.get('/urun/get-cart', function(response) {
                $('#cartBadge').text(response.cart_count || 0);
            });
        @endauth
    });
    </script>

</body>
</html>