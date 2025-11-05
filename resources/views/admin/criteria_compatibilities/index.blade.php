@extends('layouts.admin')

@section('title', 'Uyumluluk Listesi')
@section('page-title', 'Uyumluluk Listesi')
@section('page-description', 'Kriterler arası uyumlulukları görüntüleyebilir ve silebilirsiniz.')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<a href="{{ route('admin.criteria_compatibilities.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Yeni Uyumluluk Ekle
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kriter 1</th>
            <th>Değeri</th>
            <th>Kriter 2</th>
            <th>Değeri</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        @foreach($compatibilities as $compat)
        <tr>
            <td>{{ $compat->kriter1->KriterAdi }}</td>
            <td>{{ $compat->CriteriaValue1 }}</td>
            <td>{{ $compat->kriter2->KriterAdi }}</td>
            <td>{{ $compat->CriteriaValue2 }}</td>
            <td>
                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $compat->id }}">
                    <i class="fas fa-trash"></i> Sil
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Bu uyumluluk kuralını silmek istediğinize emin misiniz?')) {
            fetch('/admin/criteria_compatibilities/' + this.dataset.id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if(data.success) location.reload();
            });
        }
    });
});
</script>
@endsection
