@extends('layouts.admin')

@section('title', 'Alt Kategori Düzenle')
@section('page-title', 'Alt Kategori Düzenle')
@section('page-description', 'Alt kategoriyi düzenleyebilir ve resmini güncelleyebilirsiniz.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-edit"></i> Alt Kategori Düzenle</h4>
        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subcategories.update', $subCategory->SubCategoryId) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                        {{ old('CategoryId', $subCategory->CategoryId) == $category->CategoryId ? 'selected' : '' }}>
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
                               value="{{ old('SubCategoryName', $subCategory->SubCategoryName) }}"
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
                            @if($subCategory->SubCategoryImage)
                                <br><strong>Mevcut resim değiştirilmek isteniyorsa yeni resim seçin.</strong>
                            @endif
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
                            @if($subCategory->SubCategoryImage)
                            <img src="{{ asset($subCategory->SubCategoryImage) }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 180px; object-fit: cover;"
                                 alt="{{ $subCategory->SubCategoryName }}">
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
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
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
        }
    }
</script>
@endpush
@endsection
