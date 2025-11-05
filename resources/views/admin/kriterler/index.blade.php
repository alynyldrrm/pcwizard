@extends('layouts.admin')

@section('title', 'Kriter Yönetimi')
@section('page-title', 'Kriter Yönetimi')
@section('page-description', 'Mevcut kriterleri görüntüleyebilir, düzenleyebilir veya silebilirsiniz.')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-list"></i> Kriter Yönetimi</h4>
        <a href="{{ route('admin.kriterler.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kriter Ekle
        </a>
    </div>
    <div class="card-body">
        @if($kriterler->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Alt Kategori</th>
                        <th>Kriter Adı</th>
                        <th>Kriter Değeri</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriterler as $k)
                    <tr>
                        <td>{{ $k->KriterId }}</td>
                        <td>{{ $k->category->CategoryName }}</td>
                        <td>{{ $k->subCategory?->SubCategoryName ?? '-' }}</td>
                        <td>{{ $k->KriterAdi }}</td>
                        <td>{{ $k->KriterDegeri }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.kriterler.edit', $k->KriterId) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kriterler.destroy', $k->KriterId) }}" method="POST" onsubmit="return confirm('Silmek istediğine emin misin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Henüz kriter eklenmemiş</h5>
            <p class="text-muted">Yeni kriter eklemek için butona tıklayın.</p>
            <a href="{{ route('admin.kriterler.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Kriter Ekle
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
