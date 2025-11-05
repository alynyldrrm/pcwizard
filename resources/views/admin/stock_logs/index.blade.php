<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 rounded-lg">
            <h2 class="text-white font-bold text-2xl">Stok Kayıtları</h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow mt-6">
        <a href="{{ route('admin.stock_logs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 inline-block">
            Stok Ekle
        </a>

        <table class="w-full table-auto border-collapse border border-gray-300 mt-4">
            <thead class="bg-blue-100">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Ürün</th>
                    <th class="border px-4 py-2">Admin</th>
                    <th class="border px-4 py-2">Miktar</th>
                    <th class="border px-4 py-2">Tip</th>
                    <th class="border px-4 py-2">Tarih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="{{ $log->Tip == 'cikis' ? 'bg-red-100' : 'bg-green-100' }}">
                    <td class="border px-4 py-2">{{ $log->id }}</td>
                    <td class="border px-4 py-2">{{ $log->product->Ad ?? 'Ürün Silinmiş' }}</td>
                    <td class="border px-4 py-2">{{ $log->user->name ?? 'Bilinmiyor' }}</td>
                    <td class="border px-4 py-2">{{ $log->Miktar }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($log->Tip) }}</td>
                    <td class="border px-4 py-2">{{ $log->Tarih }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
