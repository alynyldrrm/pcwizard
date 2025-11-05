<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Başarılı - PC Toplama Sihirbazı</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .success-animation {
            animation: successPulse 2s ease-in-out infinite;
        }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
                        <span class="text-2xl">🎉</span>
                    </div>
                    <h1 class="text-3xl font-bold text-white">Sipariş Tamamlandı</h1>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('wizard.index') }}" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                        Yeni Sipariş
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Başarı Mesajı -->
        <div class="text-center mb-12">
            <div class="success-animation w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Siparişiniz Başarıyla Tamamlandı!</h2>
            <p class="text-xl text-gray-600 mb-2">Sipariş numaranız: <span class="font-bold text-blue-600">{{ $order->OrderNumber }}</span></p>
            <p class="text-gray-500">Siparişinizle ilgili güncellemeler e-posta adresinize gönderilecektir.</p>
        </div>

        <!-- Sipariş Detayları -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Sipariş Detayları</h3>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">
                    {{ $order->OrderStatus === 'Paid' ? 'Ödendi' : $order->OrderStatus }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">Sipariş Tarihi</h4>
                    <p class="text-gray-800">{{ $order->OrderDate->format('d.m.Y H:i') }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">Ödeme Tarihi</h4>
                    <p class="text-gray-800">
                        @if($order->PaymentDate)
                            {{ \Carbon\Carbon::parse($order->PaymentDate)->format('d.m.Y H:i') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">Ödeme Yöntemi</h4>
                    <p class="text-gray-800">{{ $order->PaymentMethod ?? 'Kredi Kartı' }}</p>
                </div>
            </div>

            <!-- Ürün Listesi -->
            <h4 class="text-xl font-bold text-gray-800 mb-4">Sipariş Edilen Ürünler</h4>
            <div class="space-y-4 mb-6">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
                    <img src="{{ $item->ProductImage ? '/' . $item->ProductImage : '/images/products/default.jpg' }}" 
                         alt="{{ $item->ProductName }}" 
                         class="w-16 h-16 object-cover rounded-lg"
                         onerror="this.src='/images/products/default.jpg';">
                    
                    <div class="flex-1">
                        <h5 class="font-bold text-gray-800">{{ $item->ProductBrand }} {{ $item->ProductModel }}</h5>
                        <p class="text-sm text-gray-600">{{ $item->ProductName }}</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Adet: {{ $item->Quantity }}</p>
                        <p class="font-bold text-green-600">{{ number_format($item->TotalPrice, 2, ',', '.') }} ₺</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Toplam -->
            <div class="border-t pt-6">
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-gray-800">Toplam Tutar:</span>
                    <span class="text-3xl font-bold text-green-600">{{ number_format($order->TotalAmount, 2, ',', '.') }} ₺</span>
                </div>
            </div>
        </div>

        <!-- Sonraki Adımlar -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Sonraki Adımlar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Sipariş Hazırlama</h4>
                        <p class="text-gray-600">Siparişiniz 1-2 iş günü içinde hazırlanacak ve kargoya verilecektir.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Kargo ve Teslimat</h4>
                        <p class="text-gray-600">Ürünleriniz güvenli ambalajda paketlenip adresinize gönderilecektir.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">E-posta Bildirimleri</h4>
                        <p class="text-gray-600">Her aşamada size bilgilendirme e-postaları gönderilecektir.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <span class="text-white font-bold">4</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2">Destek</h4>
                        <p class="text-gray-600">Herhangi bir sorunuz olursa müşteri hizmetlerimize ulaşabilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksiyonlar -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-center">
            <a href="{{ route('dashboard') }}" 
               class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg font-semibold text-center">
                Dashboard'a Git
            </a>
            
            <a href="{{ route('wizard.index') }}" 
               class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-blue-700 transition-all duration-200 shadow-lg font-semibold text-center">
                Yeni Sipariş Ver
            </a>
            
            <button onclick="window.print()" 
                    class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-8 py-3 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg font-semibold">
                Siparişi Yazdır
            </button>
        </div>
    </div>
</body>
</html>