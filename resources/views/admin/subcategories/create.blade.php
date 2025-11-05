@extends('layouts.admin')

@section('title', 'Alt Kategori Ekle')
@section('page-title', 'Yeni Alt Kategori Ekle')
@section('page-description', 'Alt kategoriyi ekleyebilir ve isteğe bağlı olarak resim yükleyebilirsiniz.')

@section('content')
@if($categories->count() == 0)
<div class="alert alert-warning" role="alert">
    <i class="fas fa-exclamation-triangle"></i> 
    <strong>Uyarı:</strong> Alt kategori ekleyebilmek için önce en az bir kategori oluşturmalısınız.
    <a href="{{ route('admin.categories.create') }}" class="alert-link">Kategori Ekle</a>
</div>
@else
<form method="POST" action="{{ route('admin.subcategories.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <!-- Ana Kategori Seçimi -->
            <div class="mb-3">
                <label for="CategoryId" class="form-label">
                    <i class="fas fa-tags"></i> Ana Kategori *
                </label>
                <select class="form-select @error('CategoryId') is-invalid @enderror" 
                        id="CategoryId" 
                        name="CategoryId" 
                        required>
                    <option value="">Ana kategori seçin...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryId }}" 
                                {{ old('CategoryId') == $category->CategoryId ? 'selected' : '' }}>
                            {{ $category->CategoryName }}
                        </option>
                    @endforeach
                </select>
                @error('CategoryId')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Alt Kategori Adı -->
            <div class="mb-3">
                <label for="SubCategoryName" class="form-label">
                    <i class="fas fa-sitemap"></i> Alt Kategori Adı *
                </label>
                <input type="text" 
                       class="form-control @error('SubCategoryName') is-invalid @enderror" 
                       id="SubCategoryName" 
                       name="SubCategoryName" 
                       value="{{ old('SubCategoryName') }}"
                       placeholder="Alt kategori adını girin..."
                       required>
                @error('SubCategoryName')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Alt Kategori Resmi -->
            <div class="mb-3">
                <label for="SubCategoryImage" class="form-label">
                    <i class="fas fa-image"></i> Alt Kategori Resmi
                </label>
                <input type="file" 
                       class="form-control @error('SubCategoryImage') is-invalid @enderror" 
                       id="SubCategoryImage" 
                       name="SubCategoryImage"
                       accept="image/*"
                       onchange="previewImage(this)">
                <small class="form-text text-muted">
                    Desteklenen formatlar: JPEG, PNG, JPG, GIF (Maks. 2MB)
                </small>
                @error('SubCategoryImage')
                <div class="invalid-feedback">{{ $message }}</div>
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

            <!-- Seçilen Ana Kategori Bilgisi -->
            <div class="mb-3">
                <label class="form-label">Seçilen Ana Kategori</label>
                <div id="selectedCategory" class="border rounded p-3 text-center bg-light">
                    <div class="text-muted">
                        <i class="fas fa-tags fa-2x mb-2"></i>
                        <p>Kategori seçilmedi</p>
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
                <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> İptal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Alt Kategori Ekle
                </button>
            </div>
        </div>
    </div>
</form>
@endif

<!-- Yardım Kartı -->
<div class="card mt-4">
    <div class="card-header">
        <h6><i class="fas fa-info-circle"></i> Yardım</h6>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li><strong>Ana Kategori:</strong> Alt kategorinin bağlanacağı ana kategoriyi seçin.</li>
            <li><strong>Alt Kategori Adı:</strong> En fazla 50 karakter olmalıdır.</li>
            <li><strong>Alt Kategori Resmi:</strong> Opsiyoneldir. JPEG, PNG, JPG veya GIF formatında olmalı ve 2MB'dan küçük olmalıdır.</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
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

    document.getElementById('CategoryId').addEventListener('change', function() {
        const selectedCategory = document.getElementById('selectedCategory');
        const categoryName = this.options[this.selectedIndex].text;
        
        if (this.value) {
            selectedCategory.innerHTML = `
                <div class="text-primary">
                    <i class="fas fa-tags fa-2x mb-2"></i>
                    <p><strong>${categoryName}</strong></p>
                </div>
            `;
        } else {
            selectedCategory.innerHTML = `
                <div class="text-muted">
                    <i class="fas fa-tags fa-2x mb-2"></i>
                    <p>Kategori seçilmedi</p>
                </div>
            `;
        }
    });
</script>
@endpush
