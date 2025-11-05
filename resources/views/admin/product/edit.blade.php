@extends('layouts.admin')

@section('title', 'Ürün Düzenle')
@section('page-title', 'Ürün Düzenle')
@section('page-description', 'Mevcut ürünü kategori, alt kategori ve kriterlere göre düzenleyebilirsiniz.')

@section('content')
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Geri Dön</a>

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

<form action="{{ route('admin.products.update', $product->ProductId) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <!-- Kategori -->
            <div class="mb-3">
                <label for="CategoryId" class="form-label">Kategori *</label>
                <select name="CategoryId" id="CategoryId" class="form-select" required>
                    <option value="">Seçiniz</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->CategoryId }}" {{ $product->CategoryId == $c->CategoryId ? 'selected' : '' }}>
                            {{ $c->CategoryName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Alt Kategori -->
            <div class="mb-3">
                <label for="SubCategoryId" class="form-label">Alt Kategori</label>
                <select name="SubCategoryId" id="SubCategoryId" class="form-select">
                    <option value="">Seçiniz</option>
                    @foreach($subCategories as $sc)
                        <option value="{{ $sc->SubCategoryId }}" {{ $product->SubCategoryId == $sc->SubCategoryId ? 'selected' : '' }}>
                            {{ $sc->SubCategoryName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ürün Adı -->
            <div class="mb-3">
                <label for="Ad" class="form-label">Ürün Adı *</label>
                <input type="text" name="Ad" class="form-control" value="{{ $product->Ad }}" required>
            </div>

            <!-- Fiyat -->
            <div class="mb-3">
                <label for="Fiyat" class="form-label">Fiyat *</label>
                <input type="number" step="0.01" name="Fiyat" class="form-control" value="{{ $product->Fiyat }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Marka -->
            <div class="mb-3">
                <label for="Marka" class="form-label">Marka</label>
                <input type="text" name="Marka" class="form-control" value="{{ $product->Marka }}">
            </div>

            <!-- Model -->
            <div class="mb-3">
                <label for="Model" class="form-label">Model</label>
                <input type="text" name="Model" class="form-control" value="{{ $product->Model }}">
            </div>

            <!-- Resim -->
            <div class="mb-3">
                <label for="Resim" class="form-label">Ürün Resmi</label>
                <input type="file" name="Resim" class="form-control">
                @if($product->Resim)
                    <img src="{{ asset('storage/'.$product->Resim) }}" width="80" class="mt-2">
                @endif
            </div>
        </div>
    </div>

    <!-- Açıklama -->
    <div class="mb-3">
        <label for="Aciklama" class="form-label">Açıklama</label>
        <textarea name="Aciklama" class="form-control">{{ $product->Aciklama }}</textarea>
    </div>

    <!-- Kriterler -->
    <div id="kriterler-container" class="mb-3" style="display:none;">
        <label>Kriterler</label>
        <div id="kriterler-list"></div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadCriterias(subCatId){
    $('#kriterler-list').html('');
    if(subCatId){
        $.get('/admin/subcategory-criterias/'+subCatId, function(data){
            if(data.length > 0){
                $('#kriterler-container').show();
                data.forEach(function(k){
                    let value = '{{ $productCriterias["' + k.KriterId + '"] ?? "" }}';
                    $('#kriterler-list').append(
                        '<div class="mb-2">'+
                        '<label>'+k.KriterAdi+'</label>'+
                        '<input type="text" name="Kriterler['+k.KriterId+']" class="form-control" value="'+value+'" required>'+
                        '</div>'
                    );
                });
            } else {
                $('#kriterler-container').hide();
            }
        });
    } else {
        $('#kriterler-container').hide();
    }
}

$('#CategoryId').change(function(){
    var catId = $(this).val();
    $('#SubCategoryId').html('<option value="">Seçiniz</option>');
    if(catId){
        $.get('/admin/subcategories/'+catId, function(data){
            data.forEach(function(sc){
                $('#SubCategoryId').append('<option value="'+sc.SubCategoryId+'">'+sc.SubCategoryName+'</option>');
            });
        });
    }
    $('#kriterler-list').html('');
    $('#kriterler-container').hide();
});

$('#SubCategoryId').change(function(){
    loadCriterias($(this).val());
});

$(document).ready(function(){
    var initialSubCat = $('#SubCategoryId').val();
    if(initialSubCat){
        loadCriterias(initialSubCat);
    }
});
</script>
@endsection
