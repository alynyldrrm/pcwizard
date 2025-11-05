@extends('layouts.admin')

@section('title', 'Kampanya Yönetimi')
@section('page-title', 'Kampanyalar')
@section('page-description', 'PCWizard kampanyalarını yönetebilirsiniz.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Kampanya Listesi</h4>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Yeni Kampanya
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($campaigns->isEmpty())
            <div class="alert alert-info">
                Henüz kampanya eklenmemiş.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Kampanya Adı</th>
                            <th>İndirim Türü</th>
                            <th>İndirim Değeri</th>
                            <th>Kupon Kodu</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Durum</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->CampaignId }}</td>
                            <td>
                                <strong>{{ $campaign->Name }}</strong>
                                @if($campaign->categories->count() > 0)
                                    <br><small class="text-muted">
                                        <i class="bx bx-category"></i> {{ $campaign->categories->count() }} Kategori
                                    </small>
                                @endif
                                @if($campaign->products->count() > 0)
                                    <br><small class="text-muted">
                                        <i class="bx bx-box"></i> {{ $campaign->products->count() }} Ürün
                                    </small>
                                @endif
                            </td>
                            <td>
                                @switch($campaign->DiscountType)
                                    @case('yuzde')
                                        <span class="badge bg-info">Yüzde</span>
                                        @break
                                    @case('sabit')
                                        <span class="badge bg-primary">Sabit</span>
                                        @break
                                    @case('ucretsiz_kargo')
                                        <span class="badge bg-success">Ücretsiz Kargo</span>
                                        @break
                                    @case('paket')
                                        <span class="badge bg-warning">Paket</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($campaign->DiscountType == 'yuzde')
                                    %{{ $campaign->DiscountValue }}
                                @elseif($campaign->DiscountType == 'sabit')
                                    {{ number_format($campaign->DiscountValue, 2) }} ₺
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($campaign->CouponCode)
                                    <code>{{ $campaign->CouponCode }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($campaign->StartDate)->format('d.m.Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($campaign->EndDate)->format('d.m.Y') }}</td>
                            <td>
                                @if($campaign->IsActive)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.campaigns.edit', $campaign->CampaignId) }}" 
                                       class="btn btn-info" 
                                       title="Düzenle">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.campaigns.destroy', $campaign->CampaignId) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bu kampanyayı silmek istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Sil">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection