@extends('layouts.admin')

@section('title', 'Mesaj Detayı')
@section('page-title', 'Mesaj Detayı')
@section('page-description', 'Kullanıcının mesaj detaylarını görüntüleyin.')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <p><strong>Ad:</strong> {{ $message->name }}</p>
    <p><strong>E-posta:</strong> {{ $message->email }}</p>
    <p><strong>Konu:</strong> {{ $message->subject }}</p>
    <p class="mt-4"><strong>Mesaj:</strong></p>
    <p class="bg-gray-100 p-4 rounded mt-2 whitespace-pre-wrap">{{ $message->message }}</p>
</div>

<div class="mt-6 flex gap-4">
    <a href="{{ route('admin.messages.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Geri Dön</a>
    <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">
        @csrf 
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">Sil</button>
    </form>
</div>
@endsection
