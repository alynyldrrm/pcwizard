@extends('layouts.frontend')

@section('title', 'Ana Sayfa - Avantaj Bilişim')


@section('content')

<!-- Hero Slider Section -->
<section class="relative">
    <div class="hero-swiper swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide relative" style="background-image: url('{{ asset('images/avantaj1.png') }}');">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
                    <div class="text-white max-w-2xl">
                        <h1 class="text-5xl font-bold mb-6">En Son Teknoloji Ürünleri</h1>
                        <p class="text-xl mb-8">Yüksek performanslı işlemciler, ekran kartları ve daha fazlası için doğru adrestesiniz.</p>
                        <div class="flex space-x-4">
                            <a href="#urunler" class="gradient-blue text-white px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Ürünleri İncele
                            </a>
                            @auth
                                <a href="{{ route('wizard.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                                    PC Toplama Sihirbazı
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                                    PC Toplama Sihirbazı
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="swiper-slide relative" style="background-image: url('{{ asset('images/avantaj2.png') }}');">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
                    <div class="text-white max-w-2xl">
                        <h1 class="text-5xl font-bold mb-6">Gaming PC'ler</h1>
                        <p class="text-xl mb-8">Oyun deneyiminizi bir üst seviyeye taşıyacak özel yapım gaming bilgisayarlar.</p>
                        <div class="flex space-x-4">
                            <a href="#gaming" class="gradient-red text-white px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Gaming PC'leri Gör
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="swiper-slide relative" style="background-image: url('{{ asset('images/avantaj3.png') }}');">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
                    <div class="text-white max-w-2xl">
                        <h1 class="text-5xl font-bold mb-6">İş Dünyası İçin Çözümler</h1>
                        <p class="text-xl mb-8">Ofis ve kurumsal ihtiyaçlarınız için güvenilir teknoloji çözümleri.</p>
                        <div class="flex space-x-4">
                            <a href="#business" class="bg-white text-gray-800 px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                                Kurumsal Çözümler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Popular Categories -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Popüler Kategoriler</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @if(isset($categories))
                @foreach($categories->take(6) as $category)
                    <a href="{{ route('urunler', ['category' => $category->CategoryId]) }}" class="category-card bg-white rounded-xl shadow-sm p-6 text-center group">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            @if($category->CategoryImage)
                                <img src="{{ asset($category->CategoryImage) }}" alt="{{ $category->CategoryName }}" class="w-10 h-10 object-contain">
                            @else
                                <i class="fas fa-microchip text-2xl text-blue-600"></i>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                            {{ $category->CategoryName }}
                        </h3>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shipping-fast text-2xl text-green-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Ücretsiz Kargo</h3>
                <p class="text-gray-600 text-sm">250 TL üzeri alışverişlerinizde ücretsiz kargo</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Güvenli Ödeme</h3>
                <p class="text-gray-600 text-sm">SSL sertifikası ile güvenli ödeme imkanı</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-headset text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold mb-2">7/24 Destek</h3>
                <p class="text-gray-600 text-sm">Uzman ekibimizden sürekli destek</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-sync-alt text-2xl text-orange-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Kolay İade</h3>
                <p class="text-gray-600 text-sm">14 gün içinde kolay iade imkanı</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-12 gradient-blue text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Kampanya ve İndirimlerden Haberdar Olun</h2>
        <p class="text-xl mb-8">E-bültenimize abone olun, özel fırsatları kaçırmayın!</p>
        
        <div class="max-w-md mx-auto">
            <div class="flex">
                <input type="email" placeholder="E-posta adresiniz" 
                       class="flex-1 px-4 py-3 rounded-l-lg border-0 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-800">
                <button class="bg-red-600 text-white px-6 py-3 rounded-r-lg hover:bg-red-700 font-semibold transition-colors">
                    Abone Ol
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">İletişime Geçin</h2>
            <p class="text-gray-600">Size nasıl yardımcı olabiliriz?</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">Telefon</h4>
                        <p class="text-gray-600">0850 533 3444</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">E-posta</h4>
                        <p class="text-gray-600">info@avantajbilisim.com</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">Adres</h4>
                        <p class="text-gray-600">Konya, Türkiye</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" name="name" placeholder="Adınız Soyadınız" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <input type="email" name="email" placeholder="E-posta Adresiniz" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <input type="text" name="subject" placeholder="Konu" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <textarea name="message" rows="5" placeholder="Mesajınız" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    <button type="submit" class="w-full gradient-blue text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Mesaj Gönder
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Swiper
    const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    // Categories dropdown
    let categoryTimeout;
    
    $('#categoriesBtn').on('mouseenter', function() {
        clearTimeout(categoryTimeout);
        $('#categoryDropdown').removeClass('hidden');
    });

    $('.category-menu').on('mouseleave', function() {
        categoryTimeout = setTimeout(function() {
            $('#categoryDropdown').addClass('hidden');
        }, 300);
    });

    $('#categoryDropdown').on('mouseenter', function() {
        clearTimeout(categoryTimeout);
    });

    // Cart functionality
    let cartCount = 0;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Cart modal
    $('#cartBtn').click(function() {
        loadCart();
        $('#cartModal').removeClass('hidden');
    });

    $('#closeCart').click(function() {
        $('#cartModal').addClass('hidden');
    });

    function loadCart() {
        @auth
            $.get('/wizard/cart', function(response) {
                displayCart(response.cart_items, response.total_amount);
                cartCount = response.cart_count;
                $('#cartBadge').text(cartCount);
            }).fail(function() {
                showToast('Sepet yüklenemedi!', 'error');
            });
        @else
            displayCart([], 0);
        @endauth
    }

    function displayCart(items, total) {
        const cartContainer = $('#cart-items');
        
        if (items.length === 0) {
            cartContainer.html(`
                <div class="text-center py-12 text-gray-500" id="empty-cart">
                    <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                    <p class="text-xl mb-2">Sepetiniz boş</p>
                    <p class="text-sm">Ürün eklemek için alışverişe başlayın!</p>
                </div>
            `);
            $('#cart-total').text('0 ₺');
            return;
        }

        let html = '';
        items.forEach(function(item) {
            const imageUrl = item.ProductImage ? '/' + item.ProductImage : 'https://via.placeholder.com/80x80/E5E7EB/6B7280?text=Ürün';
            const price = parseFloat(item.UnitPrice).toLocaleString('tr-TR');
            const totalPrice = parseFloat(item.TotalPrice).toLocaleString('tr-TR');
            
            html += `
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <img src="${imageUrl}" alt="${item.ProductName}" class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${item.ProductBrand} ${item.ProductModel}</h4>
                        <p class="text-sm text-gray-600">${item.ProductName}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="font-bold text-blue-600">${price} ₺</span>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Adet: ${item.Quantity}</span>
                                <span class="font-bold text-green-600">${totalPrice} ₺</span>
                                <button class="text-red-500 hover:text-red-700 remove-from-cart" data-cart-item-id="${item.CartItemId}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartContainer.html(html);
        $('#cart-total').text(parseFloat(total).toLocaleString('tr-TR') + ' ₺');
    }

    // Remove from cart
    $(document).on('click', '.remove-from-cart', function() {
        const cartItemId = $(this).data('cart-item-id');
        
        $.post('/wizard/remove-from-cart', { cart_item_id: cartItemId }, function(response) {
            if (response.success) {
                cartCount = response.cart_count;
                $('#cartBadge').text(cartCount);
                loadCart();
                showToast(response.message, 'success');
            }
        });
    });

    // Complete order
    $('#completeOrder').click(function() {
        if (cartCount === 0) {
            showToast('Sepetiniz boş!', 'warning');
            return;
        }

        if (confirm('Siparişi tamamlamak istediğinizden emin misiniz?')) {
            $.post('/wizard/complete-order', function(response) {
                if (response.success) {
                    $('#cartModal').addClass('hidden');
                    showToast('Sipariş oluşturuldu!', 'success');
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1500);
                }
            }).fail(function(xhr) {
                showToast('Sipariş oluşturulamadı!', 'error');
            });
        }
    });

    // Load initial cart count
    @auth
        $.get('/wizard/cart', function(response) {
            cartCount = response.cart_count;
            $('#cartBadge').text(cartCount);
        });
    @endauth

    // Toast notification function
    function showToast(message, type = 'info') {
        const bgColor = type === 'success' ? 'bg-green-500' : 
                      type === 'error' ? 'bg-red-500' : 
                      type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                   type === 'error' ? 'fa-exclamation-circle' : 
                   type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';

        const toast = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3 transform translate-x-full opacity-0 transition-all duration-300">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.removeClass('translate-x-full opacity-0');
        }, 100);

        setTimeout(() => {
            toast.addClass('translate-x-full opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Smooth scrolling
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
});
</script>
@endpush