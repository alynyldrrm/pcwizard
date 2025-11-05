@extends('layouts.admin')

@section('title', 'Mesajlar')
@section('page-title', 'Mesajlar')
@section('page-description', 'Kullanıcı mesajlarını görüntüleyin ve yönetin.')

@section('content')
<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-blue-700 text-white">
            <tr>
                <th class="px-6 py-3 text-left">Ad</th>
                <th class="px-6 py-3 text-left">E-posta</th>
                <th class="px-6 py-3 text-left">Konu</th>
                <th class="px-6 py-3 text-left">Tarih</th>
                <th class="px-6 py-3 text-left">İşlemler</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($messages as $msg)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $msg->name }}</td>
                <td class="px-6 py-4">{{ $msg->email }}</td>
                <td class="px-6 py-4">{{ $msg->subject }}</td>
                <td class="px-6 py-4">{{ $msg->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="text-blue-600 hover:underline">Görüntüle</a>
                    <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Sil</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection
