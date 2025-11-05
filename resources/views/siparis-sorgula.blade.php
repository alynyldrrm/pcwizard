<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Sorgula</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white shadow-md rounded-lg w-full max-w-xl p-6">
        <h1 class="text-2xl font-bold mb-4 text-center text-blue-600">Sipariş Sorgulama</h1>

        <!-- Hata mesajı -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Sipariş numarası formu -->
        <form action="{{ route('order.query') }}" method="POST" class="mb-6">
            @csrf
            <label for="order_number" class="block mb-2 font-semibold">Sipariş Numarası:</label>
            <input type="text" name="order_number" id="order_number" required
                class="w-full border border-gray-300 rounded px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Sorgula
            </button>
        </form>

        <!-- Sipariş detayları -->
        @isset($orderData)
            <div class="border-t border-gray-200 pt-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">Sipariş Detayları</h2>
                <p><strong>Sipariş Numarası:</strong> {{ $orderData->OrderNumber }}</p>
                <p><strong>Sipariş Tarihi:</strong> {{ $orderData->OrderDate }}</p>
                <p><strong>Toplam Tutar:</strong> {{ $orderData->TotalAmount }}₺</p>

                <h3 class="text-lg font-semibold text-gray-700 mt-4 mb-2">Sipariş Ürünleri</h3>
                <ul class="list-disc list-inside">
                    @foreach($orderItems as $item)
                        <li>{{ $item->ProductName }} - {{ $item->Quantity }} adet - {{ $item->Price }}₺</li>
                    @endforeach
                </ul>
            </div>
        @endisset

    </div>

</body>
</html>
