@extends('layouts.admin')

@section('title', 'Yeni Ürün Ekle')
@section('page-title', 'Yeni Ürün Ekle')
@section('page-description', 'Kategori ve alt kategoriye bağlı yeni ürün ekleyebilirsiniz.')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <!-- Kategori Seçimi -->
            <div class="mb-3">
                <label for="CategoryId" class="form-label"><i class="fas fa-tags"></i> Kategori *</label>
                <select name="CategoryId" id="CategoryId" class="form-select" required>
                    <option value="">Kategori seçiniz...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryId }}" {{ old('CategoryId') == $category->CategoryId ? 'selected' : '' }}>
                            {{ $category->CategoryName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Alt Kategori Seçimi -->
            <div class="mb-3">
                <label for="SubCategoryId" class="form-label"><i class="fas fa-sitemap"></i> Alt Kategori</label>
                <select name="SubCategoryId" id="SubCategoryId" class="form-select">
                    <option value="">Önce kategori seçin...</option>
                </select>
            </div>

            <!-- Ürün Adı -->
            <div class="mb-3">
                <label for="Ad" class="form-label"><i class="fas fa-box"></i> Ürün Adı *</label>
                <input type="text" name="Ad" id="Ad" class="form-control" value="{{ old('Ad') }}" required>
            </div>

            <!-- Fiyat -->
            <div class="mb-3">
                <label for="Fiyat" class="form-label"><i class="fas fa-lira-sign"></i> Fiyat *</label>
                <input type="number" step="0.01" name="Fiyat" id="Fiyat" class="form-control" value="{{ old('Fiyat') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Marka -->
            <div class="mb-3">
                <label for="Marka" class="form-label"><i class="fas fa-trademark"></i> Marka</label>
                <input type="text" name="Marka" id="Marka" class="form-control" value="{{ old('Marka') }}">
            </div>

            <!-- Model -->
            <div class="mb-3">
                <label for="Model" class="form-label"><i class="fas fa-cog"></i> Model</label>
                <input type="text" name="Model" id="Model" class="form-control" value="{{ old('Model') }}">
            </div>

            <!-- Resim -->
            <div class="mb-3">
                <label for="Resim" class="form-label"><i class="fas fa-image"></i> Ürün Resmi</label>
                <input type="file" name="Resim" id="Resim" class="form-control" accept="image/*">
                <small class="form-text text-muted">Maksimum 2MB</small>
            </div>
        </div>
    </div>

    <!-- Açıklama -->
    <div class="mb-3">
        <label for="Aciklama" class="form-label"><i class="fas fa-align-left"></i> Açıklama</label>
        <textarea name="Aciklama" id="Aciklama" class="form-control" rows="3">{{ old('Aciklama') }}</textarea>
    </div>

    <!-- Kriterler -->
    <div id="kriterler-container" class="mb-3" style="display: none;">
        <h5><i class="fas fa-list-check"></i> Ürün Kriterleri</h5>
        <div class="card">
            <div class="card-body">
                <div id="kriterler-list"></div>
            </div>
        </div>
    </div>

    <hr>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> İptal
        </a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Ürün Ekle
        </button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#CategoryId').change(function() {
        var categoryId = $(this).val();
        var subCategorySelect = $('#SubCategoryId');

        subCategorySelect.html('<option value="">Alt kategori yükleniyor...</option>');
        $('#kriterler-container').hide();
        $('#kriterler-list').html('');

        if (categoryId) {
            $.get('/admin/subcategories-by-category/' + categoryId)
                .done(function(data) {
                    subCategorySelect.html('<option value="">Alt kategori seçiniz...</option>');
                    if (data.length > 0) {
                        $.each(data, function(index, subCategory) {
                            subCategorySelect.append('<option value="' + subCategory.SubCategoryId + '">' + subCategory.SubCategoryName + '</option>');
                        });
                    } else {
                        subCategorySelect.html('<option value="">Bu kategoriye ait alt kategori yok</option>');
                    }
                })
                .fail(function() {
                    subCategorySelect.html('<option value="">Hata oluştu</option>');
                });
        } else {
            subCategorySelect.html('<option value="">Önce kategori seçin...</option>');
        }
    });

    $('#SubCategoryId').change(function() {
        var subCategoryId = $(this).val();
        var kriterlerContainer = $('#kriterler-container');
        var kriterlerList = $('#kriterler-list');

        if (subCategoryId) {
            $.get('/admin/criterias-by-subcategory/' + subCategoryId)
                .done(function(data) {
                    if (data.length > 0) {
                        kriterlerContainer.show();
                        kriterlerList.html('');
                        $.each(data, function(index, kriter) {
                            var inputHtml = '';
                            if (kriter.kriterValues && kriter.kriterValues.length > 0) {
                                inputHtml = '<select name="Kriterler[' + kriter.KriterAdi + ']" class="form-select">';
                                inputHtml += '<option value="">Seçiniz...</option>';
                                $.each(kriter.kriterValues, function(i, value) {
                                    inputHtml += '<option value="' + value.trim() + '">' + value.trim() + '</option>';
                                });
                                inputHtml += '</select>';
                            } else {
                                inputHtml = '<input type="text" name="Kriterler[' + kriter.KriterAdi + ']" class="form-control" placeholder="' + kriter.KriterAdi + ' değerini girin...">';
                            }
                            kriterlerList.append(
                                '<div class="mb-3">' +
                                '<label class="form-label"><i class="fas fa-check-circle"></i> ' + kriter.KriterAdi + '</label>' +
                                inputHtml +
                                '</div>'
                            );
                        });
                    } else {
                        kriterlerContainer.hide();
                    }
                })
                .fail(function(xhr, status, error) {
                    kriterlerContainer.hide();
                    alert('Kriterler yüklenirken bir hata oluştu!');
                });
        } else {
            kriterlerContainer.hide();
        }
    });
});
</script>
@endsection
