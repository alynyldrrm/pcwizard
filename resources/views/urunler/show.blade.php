<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->Ad }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" 
             alt="{{ $product->Ad }}" 
             class="w-full h-96 object-cover">

        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $product->Ad }}</h1>
            <p class="text-gray-600 mb-2">{{ $product->Marka }} {{ $product->Model }}</p>
            <p class="text-blue-600 text-xl font-bold mb-4">{{ number_format($product->Fiyat, 2) }} ₺</p>

            <p class="mb-4">{{ $product->Aciklama ?? 'Açıklama yok.' }}</p>

            <h3 class="text-lg font-semibold mb-2">Özellikler</h3>
            <p class="mb-4">{{ $product->Ozellikler ?? '-' }}</p>

            <a href="{{ route('urunler.index') }}" 
               class="inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                ← Geri Dön
            </a>
        </div>
    </div>
</div>



</body>
</html>
