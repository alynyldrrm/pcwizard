<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Misafir Ödeme</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-effect { background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.18); }
        .card-input { transition: all 0.3s ease; }
        .card-input:focus { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .processing { display: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">

    <!-- Header -->
    <div class="gradient-bg shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Ödeme</h1>
            <a href="{{ route('urun.get-cart') }}" class="glass-effect text-white px-4 py-2 rounded-xl hover:bg-opacity-30 transition-all duration-200">
                Geri Dön
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">
        <!-- Sipariş Özeti -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-4">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Sipariş Özeti</h3>
                <p class="text-sm text-gray-600 mb-1">Sipariş No: <span class="font-semibold">{{ $order->OrderNumber }}</span></p>
                <p class="text-sm text-gray-600 mb-4">Tarih: <span class="font-semibold">{{ \Carbon\Carbon::parse($order->OrderDate)->format('d.m.Y H:i') }}</span></p>

                <div class="space-y-4 max-h-64 overflow-y-auto mb-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                        <img src="{{ $item->ProductImage ? '/' . $item->ProductImage : '/images/products/default.jpg' }}" alt="{{ $item->ProductName }}" class="w-12 h-12 object-cover rounded-lg">
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

                <div class="border-t pt-4 flex justify-between items-center text-xl font-bold">
                    <span class="text-gray-800">Toplam:</span>
                    <span class="text-green-600">{{ number_format($order->TotalAmount, 2, ',', '.') }} ₺</span>
                </div>
            </div>
        </div>

        <!-- Ödeme Formu -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Ödeme Bilgileri</h3>

                <form id="paymentForm" class="space-y-6">
                    <input type="hidden" name="order_id" value="{{ $order->OrderId }}">

                    <!-- Kart Bilgileri -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-4">Kart Bilgileri</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label>Kart Üzerindeki İsim</label>
                                <input type="text" name="card_name" required class="card-input w-full px-4 py-3 border rounded-xl" placeholder="Örn: AHMET YILMAZ">
                            </div>
                            <div class="md:col-span-2">
                                <label>Kart Numarası</label>
                                <input type="text" name="card_number" maxlength="19" required class="card-input w-full px-4 py-3 border rounded-xl" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label>Ay</label>
                                    <select name="expiry_month" required class="card-input w-full px-3 py-3 border rounded-xl">
                                        <option value="">Ay</option>
                                        @for($i=1;$i<=12;$i++)
                                        <option value="{{ $i }}">{{ str_pad($i,2,'0',STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label>Yıl</label>
                                    <select name="expiry_year" required class="card-input w-full px-3 py-3 border rounded-xl">
                                        <option value="">Yıl</option>
                                        @for($i=2024;$i<=2034;$i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label>CVV</label>
                                    <input type="text" name="cvv" maxlength="3" required class="card-input w-full px-3 py-3 border rounded-xl" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fatura Adresi -->
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-4">Fatura Adresi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label>Adres</label>
                                <textarea name="billing_address" required rows="3" class="card-input w-full px-4 py-3 border rounded-xl" placeholder="Tam adresinizi yazın..."></textarea>
                            </div>
                            <div>
                                <label>Şehir</label>
                                <input type="text" name="billing_city" required class="card-input w-full px-4 py-3 border rounded-xl" placeholder="Şehir">
                            </div>
                            <div>
                                <label>Telefon</label>
                                <input type="tel" name="billing_phone" required class="card-input w-full px-4 py-3 border rounded-xl" placeholder="0555 123 45 67">
                            </div>
                        </div>
                    </div>

                    <!-- Ödeme Butonu -->
                    <div class="pt-6">
                        <button type="submit" id="payBtn" class="w-full bg-green-500 text-white py-4 rounded-xl font-bold text-lg">
                            <span id="payBtnText"> {{ number_format($order->TotalAmount,2,',','.') }} ₺ Öde </span>
                            <span id="payBtnProcessing" class="processing">İşleniyor...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Kart numarası formatlama
        $('#cardNumber').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            $(this).val(value);
        });

        // Sadece rakam girişi için CVV
        $('#cvv').on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });

        // Form gönderimi
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();

            $('#payBtn').prop('disabled', true);
            $('#payBtnText').hide();
            $('#payBtnProcessing').show();

            $.ajax({
                url: '{{ route("guest.process-payment") }}', // <- Burayı düzelttik
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1500);
                    } else {
                        showToast(response.error || 'Ödeme başarısız!', 'error');
                        resetPayButton();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Ödeme işlemi başarısız!';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    showToast(errorMsg, 'error');
                    resetPayButton();
                }
            });
        });

        function resetPayButton() {
            $('#payBtn').prop('disabled', false);
            $('#payBtnText').show();
            $('#payBtnProcessing').hide();
        }

        function showToast(message, type) {
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
            }, 4000);
        }
    });


    </script>
</body>
</html>
