@extends('layouts.frontend')

@section('title', 'Sipariş Özeti - Avantaj Bilişim')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="/" class="text-gray-500 hover:text-red-500 mr-2"><i class="fas fa-home"></i></a>
        <span class="text-gray-400 mr-2">></span>
        <a href="/urunler" class="text-gray-500 hover:text-red-500 mr-2">Ürünler</a>
        <span class="text-gray-400 mr-2">></span>
        <span class="text-gray-700 font-medium">Sipariş Özeti</span>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        <i class="fas fa-shopping-cart mr-3 text-red-500"></i>Sipariş Özeti
    </h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sol taraf: Sepet içeriği -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-box mr-2 text-blue-600"></i>
                    Sepetinizdeki Ürünler ({{ count($cartItems) }} ürün)
                </h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-4 border-b pb-4 hover:bg-gray-50 transition p-3 rounded-lg">
                        <img src="{{ asset($item->ProductImage ?? 'images/no-image.png') }}" 
                             alt="{{ $item->ProductName }}"
                             class="w-24 h-24 object-contain border rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg">{{ $item->ProductBrand }} {{ $item->ProductModel }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $item->ProductName }}</p>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="text-red-500 font-bold">{{ number_format($item->UnitPrice, 2, ',', '.') }} ₺</span>
                                <span class="text-gray-500">×</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-semibold">{{ $item->Quantity }} adet</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-red-600">{{ number_format($item->TotalPrice, 2, ',', '.') }} ₺</p>
                            <p class="text-xs text-gray-500 mt-1">Ara Toplam</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-6 border-t-2 border-gray-200">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span class="text-gray-700">Genel Toplam:</span>
                        <span class="text-red-500">{{ number_format($totalAmount, 2, ',', '.') }} ₺</span>
                    </div>
                    <div class="flex items-center mt-3 text-sm text-green-600">
                        <i class="fas fa-truck mr-2"></i>
                        <span>Ücretsiz kargo dahildir</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sağ taraf: Checkout seçenekleri -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-6 text-gray-800">Nasıl Devam Etmek İstersiniz?</h2>
                
                @auth
                    <div class="space-y-4">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <p class="text-sm text-gray-700">Hoş geldiniz,</p>
                            <p class="font-bold text-blue-700">{{ Auth::user()->name }}</p>
                        </div>
                        <form action="{{ route('wizard.complete-order') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 text-white py-4 rounded-xl font-bold hover:bg-green-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-credit-card mr-2"></i>Ödemeye Geç
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-4">
                        <a href="{{ route('login') }}?redirect=checkout" 
                           class="block w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-center hover:bg-blue-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
                        </a>
                        
                        <a href="{{ route('register') }}?redirect=checkout" 
                           class="block w-full bg-purple-600 text-white py-4 rounded-xl font-bold text-center hover:bg-purple-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-user-plus mr-2"></i>Üye Ol
                        </a>
                        
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-3 bg-white text-gray-600 font-semibold">VEYA</span>
                            </div>
                        </div>
                        
                        <!-- DÜZENLENDİ: BUTTON TYPE BUTTON -->
                        <button type="button" id="guestCheckoutBtn" 
                                class="w-full bg-gray-600 text-white py-4 rounded-xl font-bold hover:bg-gray-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-shopping-bag mr-2"></i>Üye Olmadan Devam Et
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center mt-3">
                            Üye olmadan alışverişinizi tamamlayabilirsiniz
                        </p>
                    </div>
                @endauth
                
                <div class="mt-6 pt-6 border-t space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <span>Güvenli Ödeme - SSL Sertifikalı</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-truck text-blue-600"></i>
                        </div>
                        <span>Ücretsiz Kargo - Türkiye Geneli</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="bg-purple-100 p-2 rounded-full mr-3">
                            <i class="fas fa-undo text-purple-600"></i>
                        </div>
                        <span>14 Gün İade Garantisi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Guest Checkout Modal -->
<div id="guestCheckoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-gray-600 to-gray-800 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">
                    <i class="fas fa-user-circle mr-2"></i>Misafir Bilgileri
                </h2>
                <button id="closeGuestModal" class="text-white hover:text-gray-300 text-3xl leading-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-sm mt-2 text-gray-200">Lütfen iletişim bilgilerinizi girin</p>
        </div>
        <form id="guestCheckoutForm" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700">
                    <i class="fas fa-user mr-1 text-gray-400"></i>Ad Soyad *
                </label>
                <input type="text" name="guest_name" required class="w-full p-3 border rounded-lg" placeholder="Ahmet Yılmaz">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700">
                    <i class="fas fa-envelope mr-1 text-gray-400"></i>E-posta *
                </label>
                <input type="email" name="guest_email" required class="w-full p-3 border rounded-lg" placeholder="ornek@email.com">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700">
                    <i class="fas fa-phone mr-1 text-gray-400"></i>Telefon *
                </label>
                <input type="tel" name="guest_phone" required class="w-full p-3 border rounded-lg" placeholder="0555 123 45 67">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700">
                    <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>Adres *
                </label>
                <textarea name="guest_address" required class="w-full p-3 border rounded-lg" rows="3" placeholder="Mahalle, Sokak, No..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-gray-700">
                    <i class="fas fa-city mr-1 text-gray-400"></i>Şehir *
                </label>
                <input type="text" name="guest_city" required class="w-full p-3 border rounded-lg" placeholder="İstanbul">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white py-4 rounded-xl font-bold hover:bg-green-600">
                <i class="fas fa-arrow-right mr-2"></i>Ödemeye Geç
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // CSRF ayarı
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Modal açma
    $('#guestCheckoutBtn').click(function() {
        $('#guestCheckoutModal').removeClass('hidden');
    });

    // Modal kapatma (arka plan veya X buton)
    $('#closeGuestModal, #guestCheckoutModal').click(function(e) {
        if(e.target === this) {
            $('#guestCheckoutModal').addClass('hidden');
        }
    });

    // Form submit AJAX
    $('#guestCheckoutForm').submit(function(e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>İşleniyor...');

        $.ajax({
            url: '{{ route("urun.guest-order") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    window.location.href = res.redirect_url;
                } else {
                    alert(res.error || 'Bir hata oluştu');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-arrow-right mr-2"></i>Ödemeye Geç');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Sipariş oluşturulamadı!');
                submitBtn.prop('disabled', false).html('<i class="fas fa-arrow-right mr-2"></i>Ödemeye Geç');
            }
        });
    });
});
</script>
@endpush
