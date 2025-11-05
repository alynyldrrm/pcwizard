<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 rounded-lg">
            <h2 class="text-white font-bold text-2xl">Stok Ekle / Çıkış</h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mt-6 max-w-xl mx-auto">
        <form action="{{ route('admin.stock_logs.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700">Ürün</label>
                <select name="ProductId" class="w-full border px-3 py-2 rounded">
                    @foreach($products as $product)
                        <option value="{{ $product->ProductId }}">{{ $product->Ad }} ({{ $product->Marka }} - {{ $product->Model }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Miktar</label>
                <input type="number" name="Miktar" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div>
                <label class="block text-gray-700">Tip</label>
                <select name="Tip" class="w-full border px-3 py-2 rounded" required>
                    <option value="ekleme">Ekleme</option>
                    <option value="cikis">Çıkış</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Kaydet
            </button>
        </form>
    </div>
</x-app-layout>
