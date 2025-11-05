<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Ürünler</h1>

    <!-- Filtreleme Formu -->
    <form method="GET" action="{{ route('urunler.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-gray-100 p-4 rounded-lg">
        <select name="category" class="p-2 border rounded">
            <option value="">Kategori Seç</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->CategoryId }}" {{ request('category') == $cat->CategoryId ? 'selected' : '' }}>
                    {{ $cat->CategoryName }}
                </option>
            @endforeach
        </select>

        <input type="text" name="brand" placeholder="Marka" value="{{ request('brand') }}" class="p-2 border rounded">
        <input type="number" name="min_price" placeholder="Min Fiyat" value="{{ request('min_price') }}" class="p-2 border rounded">
        <input type="number" name="max_price" placeholder="Max Fiyat" value="{{ request('max_price') }}" class="p-2 border rounded">
        <input type="text" name="search" placeholder="Ara..." value="{{ request('search') }}" class="p-2 border rounded col-span-2 md:col-span-1">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrele</button>
    </form>

    <!-- Ürün Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="relative bg-white shadow-md rounded-lg overflow-hidden group">
                <img src="{{ asset($product->Resim ?? 'images/no-image.png') }}" 
                     alt="{{ $product->Ad }}" 
                     class="w-full h-48 object-cover transform group-hover:scale-105 transition duration-300">

                <div class="p-4">
                    <h2 class="text-lg font-semibold">{{ $product->Ad }}</h2>
                    <p class="text-gray-600">{{ $product->Marka }} {{ $product->Model }}</p>
                    <p class="text-blue-600 font-bold mt-2">{{ number_format($product->Fiyat, 2) }} ₺</p>
                </div>

                <!-- Hoverda İncele -->
                <a href="{{ route('urunler.show', $product->ProductId) }}"
                   class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white text-lg font-bold opacity-0 group-hover:opacity-100 transition">
                    İncele
                </a>
            </div>
        @empty
            <p class="col-span-4 text-center text-gray-500">Ürün bulunamadı.</p>
        @endforelse
    </div>



        
    <!-- Sayfalama -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>

</body>
</html>
