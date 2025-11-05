@extends('layouts.admin')

@section('title', 'Uyumluluk Ekle')
@section('page-title', 'Yeni Uyumluluk Ekle')
@section('page-description', 'Kriterler arası uyumluluk ekleyin.')

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.criteria_compatibilities.store') }}" method="POST">
    @csrf

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="CriteriaId1" class="form-label">Kriter 1</label>
            <select name="CriteriaId1" id="CriteriaId1" class="form-control" required>
                <option value="">Seçiniz</option>
                @foreach($criterias as $c)
                    <option value="{{ $c->KriterId }}">
                        {{ $c->category->CategoryName ?? '' }} > {{ $c->subCategory->SubCategoryName ?? '' }} > {{ $c->KriterAdi }}: {{ $c->KriterDegeri }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="CriteriaValue1" class="form-label">Değer</label>
            <select name="CriteriaValue1" id="CriteriaValue1" class="form-control" required>
                <option value="">Önce Kriter 1 seçin</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="CriteriaId2" class="form-label">Kriter 2</label>
            <select name="CriteriaId2" id="CriteriaId2" class="form-control" required>
                <option value="">Seçiniz</option>
                @foreach($criterias as $c)
                    <option value="{{ $c->KriterId }}">
                        {{ $c->category->CategoryName ?? '' }} > {{ $c->subCategory->SubCategoryName ?? '' }} > {{ $c->KriterAdi }}: {{ $c->KriterDegeri }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="CriteriaValue2" class="form-label">Değer</label>
            <select name="CriteriaValue2" id="CriteriaValue2" class="form-control" required>
                <option value="">Önce Kriter 2 seçin</option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.criteria_compatibilities.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> İptal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Kaydet
        </button>
    </div>
</form>

<div class="card mt-4">
    <div class="card-header">
        <h6><i class="fas fa-info-circle"></i> Yardım</h6>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>Aynı kriter kendisiyle uyumlu olamaz.</li>
            <li>Uyumluluk eklerken ters uyumluluk kontrolü otomatik yapılır.</li>
            <li>Önce kriteri seçin, sonra değer seçimi yapılabilir.</li>
        </ul>
    </div>
</div>

<script>
function fetchValues(criteriaId, targetSelectId) {
    let select = document.getElementById(targetSelectId);
    select.innerHTML = '<option value="">Önce kriter seçin</option>';
    if(!criteriaId) return;

    fetch('/admin/criteria_values/' + criteriaId)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                data.values.forEach(v => {
                    let opt = document.createElement('option');
                    opt.value = v.KriterDegeri;
                    opt.textContent = v.KriterDegeri;
                    select.appendChild(opt);
                });
            }
        });
}

document.getElementById('CriteriaId1').addEventListener('change', function(){
    fetchValues(this.value, 'CriteriaValue1');
});

document.getElementById('CriteriaId2').addEventListener('change', function(){
    fetchValues(this.value, 'CriteriaValue2');
});
</script>
@endsection
