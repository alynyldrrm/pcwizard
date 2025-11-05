@extends('layouts.admin')

@section('title', 'Ürünler')
@section('page-title', 'Ürünler')
@section('page-description', 'Mevcut ürünleri görüntüleyebilir, düzenleyebilir veya silebilirsiniz.')

@section('content')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Yeni Ürün Ekle
</a>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($products->count() > 0)
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Kategori</th>
                <th>Alt Kategori</th>
                <th>Fiyat</th>
                <th>Marka</th>
                <th>Model</th>
                <th>Resim</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->ProductId }}</td>
                <td>{{ $p->Ad }}</td>
                <td>{{ $p->category->CategoryName ?? '-' }}</td>
                <td>{{ $p->subCategory->SubCategoryName ?? '-' }}</td>
                <td>{{ $p->Fiyat }}</td>
                <td>{{ $p->Marka }}</td>
                <td>{{ $p->Model }}</td>
                <td>
                    @if($p->Resim)
                        <img src="{{ asset($p->Resim) }}" width="50" alt="{{ $p->Ad }}">
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $p->ProductId) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Düzenle
                    </a>
                    <form action="{{ route('admin.products.destroy', $p->ProductId) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Silmek istediğine emin misin?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Sil
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-4">
    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
    <h5 class="text-muted">Henüz ürün eklenmemiş</h5>
    <p class="text-muted">Yeni ürün eklemek için butona tıklayın.</p>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Ürün Ekle
    </a>
</div>
@endif
@endsection
