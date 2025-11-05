@extends('layouts.admin')

@section('title', 'Kategori Yönetimi')
@section('page-title', 'Kategori Yönetimi')
@section('page-description', 'PCWizard Admin Panel\'de kategorileri yönetin.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-tags"></i> Kategori Yönetimi</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Kategori Ekle
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Resim</th>
                        <th>Kategori Adı</th>
                        <th>Alt Kategori Sayısı</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->CategoryId }}</td>
                        <td>
                            @if($category->CategoryImage)
                            <img src="{{ asset($category->CategoryImage) }}" 
                                 alt="{{ $category->CategoryName }}" 
                                 class="rounded" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-image text-white"></i>
                            </div>
                            @endif
                        </td>
                        <td><strong>{{ $category->CategoryName }}</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $category->sub_categories_count }} Alt Kategori</span>
                        </td>
                        <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.categories.show', $category->CategoryId) }}" 
                                   class="btn btn-sm btn-outline-info" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category->CategoryId) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteCategory({{ $category->CategoryId }})" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Henüz kategori eklenmemiş</h5>
            <p class="text-muted">İlk kategorinizi eklemek için yukarıdaki butona tıklayın.</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> İlk Kategoriyi Ekle
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kategori Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bu kategoriyi silmek istediğinizden emin misiniz?</p>
                <p><small class="text-danger">Bu işlem geri alınamaz ve bu kategoriye bağlı tüm alt kategoriler de silinecektir.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteCategory(categoryId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/categories/${categoryId}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endsection
