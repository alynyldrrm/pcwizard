@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'PCWizard Admin Panel\'e hoş geldiniz!')

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <!-- Statistics Row -->
        <div class="row">
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-user rounded bg-label-primary p-2"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Kullanıcılar</span>
                        <h3 class="card-title mb-2">{{ \App\Models\User::count() }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Toplam</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-shield rounded bg-label-success p-2"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Admin</span>
                        <h3 class="card-title mb-2">{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Aktif</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-box rounded bg-label-info p-2"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Ürünler</span>
                        <h3 class="card-title mb-2">0</h3>
                        <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> Bekleyen</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-shopping-bag rounded bg-label-warning p-2"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Siparişler</span>
                        <h3 class="card-title mb-2">0</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Yeni</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Statistics -->
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-chart rounded bg-label-success p-2"></i>
                            </div>
                        </div>
                        <span class="d-block mb-1">Gelir</span>
                        <h3 class="card-title text-nowrap mb-1">₺0</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +0%</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-credit-card rounded bg-label-info p-2"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Satış</span>
                        <h3 class="card-title mb-2">₺0</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +0%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kampanyalar Kartı -->
<div class="col-lg-3 col-md-12 col-6 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                    <i class="menu-icon tf-icons bx bx-tag rounded bg-label-warning p-2"></i>
                </div>
            </div>
            <span class="fw-semibold d-block mb-1">Kampanyalar</span>
            <h3 class="card-title mb-2">
                <a href="{{ route('admin.campaigns.index') }}" class="stretched-link text-decoration-none text-dark">
                    {{ \App\Models\Campaign::where('IsActive', true)->count() }}
                </a>
            </h3>
            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Aktif</small>
        </div>
    </div>
</div>

<!-- Recent Activities Table -->
<div class="card">
    <h5 class="card-header">Son Aktiviteler</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Kullanıcı</th>
                    <th>Aktivite</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ now()->format('d.m.Y H:i') }}</strong></td>
                    <td>{{ auth()->user()->name }}</td>
                    <td>Admin paneline giriş yapıldı</td>
                    <td><span class="badge bg-label-success me-1">Başarılı</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
