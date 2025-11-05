@extends('layouts.admin')

@section('title', 'Kategori Ekle')
@section('page-title', 'Yeni Kategori Ekle')
@section('page-description', 'PCWizard Admin Panel\'de yeni kategori ekleyin.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-plus"></i> Yeni Kategori Ekle</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            
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
                               value="{{ old('CategoryName') }}"
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
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p>Resim seçilmedi</p>
                                </div>
                            </div>
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
                            <i class="fas fa-save"></i> Kategori Ekle
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Yardım Kartı -->
<div class="card mt-4">
    <div class="card-header">
        <h6><i class="fas fa-info-circle"></i> Yardım</h6>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li><strong>Kategori Adı:</strong> En fazla 50 karakter olmalı ve benzersiz olmalıdır.</li>
            <li><strong>Kategori Resmi:</strong> Opsiyoneldir. JPEG, PNG, JPG veya GIF formatında olmalı ve 2MB'dan küçük olmalıdır.</li>
            <li>Kategori oluşturulduktan sonra alt kategoriler ekleyebilirsiniz.</li>
        </ul>
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
        } else {
            preview.innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-muted">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <p>Resim seçilmedi</p>
                    </div>
                </div>
            `;
        }
    }
</script>
@endsection
