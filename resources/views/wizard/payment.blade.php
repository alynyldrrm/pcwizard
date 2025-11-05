<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme - PC Toplama Sihirbazı</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-effect { background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,0.18);}
        .card-input { transition: all 0.3s ease; }
        .card-input:focus { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);}
        .processing { display:none; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">

<!-- Header -->
<div class="gradient-bg shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <span class="text-2xl">💳</span>
            </div>
            <h1 class="text-3xl font-bold text-white">Ödeme</h1>
        </div>
        <a href="{{ route('wizard.index') }}" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200 flex items-center">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Geri Dön
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    <!-- Sipariş Özeti -->
    <div class="lg:w-1/3">
        <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-4">
            <div class="flex items-center mb-6">
                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                    <span class="text-white text-sm">📋</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Sipariş Özeti</h3>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Sipariş No: <span class="font-semibold">{{ $order->OrderNumber }}</span></p>
                <p class="text-sm text-gray-600">Tarih: <span class="font-semibold">{{ \Carbon\Carbon::parse($order->OrderDate)->format('d.m.Y H:i') }}</span></p>
            </div>
            <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                    <img src="{{ $item->ProductImage ? '/' . $item->ProductImage : '/images/products/default.jpg' }}" 
                         alt="{{ $item->ProductName }}" 
                         class="w-12 h-12 object-cover rounded-lg"
                         onerror="this.src='/images/products/default.jpg';">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $item->ProductBrand }} {{ $item->ProductModel }}</p>
                        <p class="text-xs text-gray-600">{{ $item->ProductName }}</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500">Adet: {{ $item->Quantity }}</span>
                            <span class="font-bold text-green-600">{{ number_format($item->TotalPrice, 2, ',', '.') }} ₺</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="border-t pt-4">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span class="text-gray-800">Toplam:</span>
                    <span class="text-green-600" id="totalAmount">{{ number_format($order->TotalAmount, 2, ',', '.') }} ₺</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ödeme Formu -->
    <div class="lg:w-2/3">
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center mb-6">
                <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-2">
                    <span class="text-white text-sm">💳</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Ödeme Bilgileri</h3>
            </div>

            <form id="paymentForm" class="space-y-6">
                <input type="hidden" name="order_id" value="{{ $order->OrderId }}">

                <!-- Kart Bilgileri -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl">
                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Kart Bilgileri
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kart Üzerindeki İsim</label>
                            <input type="text" name="card_name" id="cardName" required 
                                   class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Örn: AHMET YILMAZ">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kart Numarası</label>
                            <input type="text" name="card_number" id="cardNumber" required maxlength="19"
                                   class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="1234 5678 9012 3456">
                            <p class="text-xs text-gray-500 mt-1">Test kartları: 4111 1111 1111 1111, 5555 5555 5555 4444</p>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ay</label>
                                <select name="expiry_month" id="expiryMonth" required 
                                        class="card-input w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Ay</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i,2,'0',STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Yıl</label>
                                <select name="expiry_year" id="expiryYear" required 
                                        class="card-input w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Yıl</option>
                                    @for($i = 2024; $i <= 2034; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                <input type="text" name="cvv" id="cvv" required maxlength="3"
                                       class="card-input w-full px-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="123">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kupon Kodu -->
                <div class="bg-yellow-50 p-6 rounded-xl">
                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"></path>
                        </svg>
                        Kupon Kodu
                    </h4>
                    <div class="flex gap-2">
                        <input type="text" name="coupon_code" id="couponCode" placeholder="Kupon kodunu girin"
                               class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <button type="button" id="applyCouponBtn"
                                class="bg-yellow-400 text-white px-4 py-3 rounded-xl hover:bg-yellow-500 transition-all duration-200 font-semibold">
                            Uygula
                        </button>
                    </div>
                    <p id="couponMessage" class="text-sm mt-2 text-gray-600"></p>
                </div>

                <!-- Fatura Adresi -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-xl">
                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Fatura Adresi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                            <textarea name="billing_address" id="billingAddress" required rows="3"
                                      class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Tam adresinizi yazın..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                            <input type="text" name="billing_city" id="billingCity" required
                                   class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Şehir">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input type="tel" name="billing_phone" id="billingPhone" required
                                   class="card-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0555 123 45 67">
                        </div>
                    </div>
                </div>

                <!-- Ödeme Butonu -->
                <div class="pt-6">
                    <button type="submit" id="payBtn" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg font-bold text-lg">
                        <span id="payBtnText">
                            <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ number_format($order->TotalAmount, 2, ',', '.') }} ₺ Öde
                        </span>
                        <span id="payBtnProcessing" class="processing">
                            <svg class="w-6 h-6 inline-block mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            İşleniyor...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Kart numarası formatlama
    $('#cardNumber').on('input', function() {
        let value = $(this).val().replace(/\D/g,'');
        value = value.replace(/(\d{4})(?=\d)/g,'$1 ');
        $(this).val(value);
    });
    // CVV rakam
    $('#cvv').on('input', function() { $(this).val($(this).val().replace(/\D/g,'')); });

    // Kupon uygulama
    $('#applyCouponBtn').on('click', function() {
        let code = $('#couponCode').val().trim();
        if(!code) { 
            $('#couponMessage').text('Lütfen kupon kodu girin.').removeClass('text-green-600').addClass('text-red-500'); 
            return; 
        }
        $.post('{{ route("wizard.apply-coupon") }}', { coupon_code: code, order_id: '{{ $order->OrderId }}' }, function(res) {
            if(res.success){
                $('#couponMessage').text('Kupon uygulandı! İndirim: '+res.discount_text).removeClass('text-red-500').addClass('text-green-600');
                $('#totalAmount').text(res.new_total_formatted + ' ₺');
                $('#payBtnText').html('<svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>'+res.new_total_formatted+' ₺ Öde');
            } else {
                $('#couponMessage').text(res.error).removeClass('text-green-600').addClass('text-red-500');
            }
        }).fail(function(xhr){
            $('#couponMessage').text('Kupon doğrulama başarısız!').removeClass('text-green-600').addClass('text-red-500');
        });
    });

    // Ödeme form submit
    $('#paymentForm').on('submit', function(e){
        e.preventDefault();
        $('#payBtnText').hide(); $('#payBtnProcessing').show();
        $.post('{{ route("wizard.complete-payment") }}', $(this).serialize(), function(res){
            alert('Ödeme başarılı!'); // Daha sonra yönlendirme eklenebilir
            $('#payBtnText').show(); $('#payBtnProcessing').hide();
        }).fail(function(){
            alert('Ödeme başarısız!'); 
            $('#payBtnText').show(); $('#payBtnProcessing').hide();
        });
    });
});
</script>

</body>
</html>
