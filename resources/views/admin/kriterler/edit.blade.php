@extends('layouts.admin')

@section('title', 'Kriter Düzenle')
@section('page-title', 'Kriter Düzenle')
@section('page-description', 'Mevcut kriteri kategori ve alt kategoriye göre düzenleyebilirsiniz.')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-edit"></i> Kriter Düzenle</h4>
        <a href="{{ route('admin.kriterler.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kriterler.update', $kriter->KriterId) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="CategoryId" class="form-label"><i class="fas fa-tags"></i> Kategori *</label>
                <select class="form-select @error('CategoryId') is-invalid @enderror" name="CategoryId" id="CategoryId" required>
                    @foreach($kategoriler as $kategori)
                        <option value="{{ $kategori->CategoryId }}" @selected($kriter->CategoryId == $kategori->CategoryId)>{{ $kategori->CategoryName }}</option>
                    @endforeach
                </select>
                @error('CategoryId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="SubCategoryId" class="form-label"><i class="fas fa-sitemap"></i> Alt Kategori</label>
                <select class="form-select @error('SubCategoryId') is-invalid @enderror" name="SubCategoryId" id="SubCategoryId">
                    <option value="">Seçiniz</option>
                    @foreach($altKategoriler as $alt)
                        <option value="{{ $alt->SubCategoryId }}" data-category="{{ $alt->CategoryId }}" @selected($kriter->SubCategoryId == $alt->SubCategoryId)>{{ $alt->SubCategoryName }}</option>
                    @endforeach
                </select>
                @error('SubCategoryId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="KriterAdi" class="form-label"><i class="fas fa-list"></i> Kriter Adı *</label>
                <input type="text" class="form-control @error('KriterAdi') is-invalid @enderror" name="KriterAdi" id="KriterAdi" value="{{ old('KriterAdi', $kriter->KriterAdi) }}" required>
                @error('KriterAdi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="KriterDegeri" class="form-label"><i class="fas fa-cogs"></i> Kriter Değeri (virgülle ayır) *</label>
                <input type="text" class="form-control @error('KriterDegeri') is-invalid @enderror" name="KriterDegeri" id="KriterDegeri" value="{{ old('KriterDegeri', $kriter->KriterDegeri) }}" required>
                @error('KriterDegeri')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kriterler.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> İptal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#CategoryId').change(function() {
            var categoryId = $(this).val();
            if(categoryId === "") {
                $('#SubCategoryId option').show();
                $('#SubCategoryId').val('');
                return;
            }
            $('#SubCategoryId option').hide();
            $('#SubCategoryId option[value=""]').show();
            $('#SubCategoryId option[data-category="'+categoryId+'"]').show();
            $('#SubCategoryId').val('');
        });
    });
</script>
@endsection
