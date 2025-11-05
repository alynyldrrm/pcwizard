@extends('layouts.admin')

@section('title', 'Tüm Siparişler')
@section('page-title', 'Siparişler')
@section('page-description', 'Tüm siparişleri görüntüleyin ve detaylarına bakın.')

@section('content')
<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-blue-700 text-white">
            <tr>
                <th class="px-6 py-3 text-left">Sipariş No</th>
                <th class="px-6 py-3 text-left">Tarih</th>
                <th class="px-6 py-3 text-left">Toplam</th>
                <th class="px-6 py-3 text-left">Durum</th>
                <th class="px-6 py-3 text-left">İşlemler</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $order->OrderNumber }}</td>
                <td class="px-6 py-4">{{ $order->OrderDate->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4">{{ number_format($order->TotalAmount,2) }} ₺</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-sm font-semibold 
                        {{ $order->OrderStatus == 'Tamamlandı' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $order->OrderStatus }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <button onclick="showDetails({{ $order->OrderId }})" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Detay
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-11/12 max-w-3xl relative">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 font-bold">&times;</button>
        <div id="modal-content"></div>
    </div>
</div>

<script>
function showDetails(orderId) {
    fetch(`/admin/orders/${orderId}`)
        .then(res => res.json())
        .then(data => {
            let total = parseFloat(data.TotalAmount) || 0;

            let html = `<h2 class="text-2xl font-bold mb-4">Sipariş Detayı: ${data.OrderNumber}</h2>`;
            html += `<p class="mb-2"><strong>Kullanıcı:</strong> ${data.user.name} (${data.user.email})</p>`;
            html += `<p class="mb-2"><strong>Tarih:</strong> ${new Date(data.OrderDate).toLocaleString()}</p>`;
            html += `<p class="mb-2"><strong>Toplam:</strong> ${total.toFixed(2)} ₺</p>`;
            html += `<p class="mb-4"><strong>Durum:</strong> ${data.OrderStatus}</p>`;
            html += `<h3 class="font-semibold mb-2">Ürünler:</h3>`;
            html += `<table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-3 py-1">Ürün</th>
                                <th class="px-3 py-1">Marka</th>
                                <th class="px-3 py-1">Model</th>
                                <th class="px-3 py-1">Adet</th>
                                <th class="px-3 py-1">Birim Fiyat</th>
                                <th class="px-3 py-1">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>`;
            data.items.forEach(item => {
                html += `<tr class="border-b">
                            <td class="px-3 py-1">${item.ProductName}</td>
                            <td class="px-3 py-1">${item.ProductBrand}</td>
                            <td class="px-3 py-1">${item.ProductModel}</td>
                            <td class="px-3 py-1">${item.Quantity}</td>
                            <td class="px-3 py-1">${parseFloat(item.UnitPrice).toFixed(2)} ₺</td>
                            <td class="px-3 py-1">${parseFloat(item.TotalPrice).toFixed(2)} ₺</td>
                        </tr>`;
            });
            html += `</tbody></table>`;

            document.getElementById('modal-content').innerHTML = html;
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
        })
        .catch(err => console.error(err));
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
</script>
@endsection
