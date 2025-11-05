@extends('layouts.admin')

@section('title', 'Sipariş Detayı')
@section('page-title', 'Sipariş Detayı')
@section('page-description', 'Seçilen siparişin detaylarını görüntüleyin.')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-2xl font-bold mb-4">Sipariş: {{ $order->OrderNumber }}</h2>
    <p class="mb-2"><strong>Kullanıcı:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
    <p class="mb-2"><strong>Tarih:</strong> {{ $order->OrderDate->format('d-m-Y H:i') }}</p>
    <p class="mb-2"><strong>Toplam:</strong> {{ number_format($order->TotalAmount,2) }} ₺</p>
    <p class="mb-2"><strong>Durum:</strong> 
        <span class="px-2 py-1 rounded-full text-sm font-semibold 
            {{ $order->OrderStatus == 'Tamamlandı' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ $order->OrderStatus }}
        </span>
    </p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <h3 class="text-xl font-bold mb-4">Ürünler</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Ürün</th>
                    <th class="px-4 py-2 text-left">Marka</th>
                    <th class="px-4 py-2 text-left">Model</th>
                    <th class="px-4 py-2 text-left">Adet</th>
                    <th class="px-4 py-2 text-left">Birim Fiyat</th>
                    <th class="px-4 py-2 text-left">Toplam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $item->ProductName }}</td>
                    <td class="px-4 py-2">{{ $item->ProductBrand }}</td>
                    <td class="px-4 py-2">{{ $item->ProductModel }}</td>
                    <td class="px-4 py-2">{{ $item->Quantity }}</td>
                    <td class="px-4 py-2">{{ number_format($item->UnitPrice,2) }} ₺</td>
                    <td class="px-4 py-2">{{ number_format($item->TotalPrice,2) }} ₺</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Geri Dön</a>
</div>
@endsection
