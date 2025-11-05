<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seçilen Ürünler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8 font-sans">

    <h1 class="text-3xl font-bold text-center text-gray-900 mb-10">Seçilen Ürünler</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">

            {{-- Resim --}}
            <div class="relative w-full h-48 bg-gray-100 flex items-center justify-center p-4">
                <img src="{{ asset($product->Resim) }}" 
                     alt="{{ $product->Ad }}" 
                     class="max-h-full object-contain"
                     onerror="this.src='{{ asset('images/products/default.jpg') }}';">
            </div>

            {{-- İçerik --}}
            <div class="p-4 flex flex-col justify-between h-40">
                {{-- Başlık --}}
                <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ $product->Marka }} {{ $product->Model }}</h2>

                {{-- Açıklama --}}
                <p class="text-gray-500 text-sm mb-3 truncate">{{ $product->Ad }}</p>

                {{-- Fiyat Butonu --}}
                <a href="#" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg text-center hover:bg-green-700 transition-colors duration-200">
                    {{ number_format($product->Fiyat, 0, ',', '.') }} TL
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Toplam Fiyat --}}
    <div class="mt-8 text-right">
        @php
            $totalPrice = $products->sum('Fiyat');
        @endphp
        <p class="text-xl font-bold text-gray-800">
            Toplam Fiyat: <span class="text-green-600">{{ number_format($totalPrice, 0, ',', '.') }} TL</span>
        </p>
    </div>

</body>
</html>
