<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teşekkürler!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-md max-w-3xl w-full">
        <h1 class="text-3xl font-bold text-green-600 mb-6">Teşekkürler!</h1>
        <p class="mb-4 text-gray-700">Siparişiniz başarıyla oluşturuldu. Sipariş numaranız: 
            <span class="font-semibold text-gray-900">{{ $order->OrderNumber }}</span>
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mb-3">Sipariş Detayları:</h2>
        <ul class="mb-6 divide-y divide-gray-200">
            @foreach($order->items as $item)
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <span class="font-medium">{{ $item->ProductName }}</span>
                        <span class="text-gray-500">({{ $item->ProductBrand }} {{ $item->ProductModel }})</span>
                        <div class="text-gray-600 text-sm">Adet: {{ $item->Quantity }}</div>
                    </div>
                    <div class="font-semibold text-gray-900">
                        {{ number_format($item->TotalPrice, 2, ',', '.') }} TL
                    </div>
                </li>
            @endforeach
        </ul>

        <p class="text-xl font-semibold mb-4">Toplam Tutar: {{ number_format($order->TotalAmount, 2, ',', '.') }} TL</p>
        <p class="text-gray-700">Siparişinizi en kısa sürede işleme alacağız.</p>
        <a href="/" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
