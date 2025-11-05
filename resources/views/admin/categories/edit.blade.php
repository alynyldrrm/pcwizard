@extends('layouts.admin')

@section('title', 'Kategori Düzenle')
@section('page-title', 'Kategori Düzenle')
@section('page-description', 'PCWizard Admin Panel\'de kategori bilgilerini güncelleyin.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-edit"></i> Kategori Düzenle</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category->CategoryId) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Kategori Adı -->
                    <div class="mb-3">
                        <label for="CategoryName" class="form-label">
                            <i class="fas fa-tag"></i> Kategori Adı *
                        </label>
                        <input type="text" 
                               class="form-control @error('CategoryName') is-invalid @enderror" 
                               id="CategoryName" 
                               name="CategoryName" 
                               value="{{ old('CategoryName', $category->CategoryName) }}"
                               placeholder="Kategori adını girin..."
                               required>
                        @error('CategoryName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Kategori Resmi -->
                    <div class="mb-3">
                        <label for="CategoryImage" class="form-label">
                            <i class="fas fa-image"></i> Kategori Resmi
                        </label>
                        <input type="file" 
                               class="form-control @error('CategoryImage') is-invalid @enderror" 
                               id="CategoryImage" 
                               name="CategoryImage"
                               accept="image/*"
                               onchange="previewImage(this)">
                        <small class="form-text text-muted">
                            Desteklenen formatlar: JPEG, PNG, JPG, GIF (Maks. 2MB)
                            @if($category->CategoryImage)
                                <br><strong>Mevcut resim değiştirilmek isteniyorsa yeni resim seçin.</strong>
                            @endif
                        </small>
                        @error('CategoryImage')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Resim Önizleme -->
                    <div class="mb-3">
                        <label class="form-label">Resim Önizleme</label>
                        <div id="imagePreview" class="border rounded p-3 text-center" style="height: 200px;">
                            @if($category->CategoryImage)
                            <img src="{{ asset($category->CategoryImage) }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 180px; object-fit: cover;"
                                 alt="{{ $category->CategoryName }}">
                            @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p>Resim yok</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" 
                         class="img-fluid rounded" 
                         style="max-height: 180px; object-fit: cover;">
                `;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
