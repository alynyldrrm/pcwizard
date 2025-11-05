@extends('layouts.admin')

@section('title', isset($campaign) ? 'Kampanya Düzenle' : 'Yeni Kampanya')
@section('page-title', isset($campaign) ? 'Kampanya Düzenle' : 'Yeni Kampanya')

@section('content')
<div class="card">
    <h5 class="card-header">{{ isset($campaign) ? 'Kampanya Düzenle' : 'Yeni Kampanya Ekle' }}</h5>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($campaign) ? route('admin.campaigns.update', $campaign->CampaignId) : route('admin.campaigns.store') }}" method="POST">
            @csrf
            @if(isset($campaign))
                @method('PUT')
            @endif

            <!-- Temel Bilgiler -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kampanya Adı <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="Name" 
                               class="form-control @error('Name') is-invalid @enderror" 
                               value="{{ old('Name', $campaign->Name ?? '') }}" 
                               required
                               placeholder="Örn: Yaz İndirimi 2024">
                        @error('Name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kupon Kodu</label>
                        <input type="text" 
                               name="CouponCode" 
                               class="form-control @error('CouponCode') is-invalid @enderror" 
                               value="{{ old('CouponCode', $campaign->CouponCode ?? '') }}"
                               placeholder="Örn: YAZ2024"
                               style="text-transform: uppercase;">
                        @error('CouponCode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kupon kodsuz kampanya için boş bırakın</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Açıklama</label>
                <textarea name="Description" 
                          class="form-control @error('Description') is-invalid @enderror" 
                          rows="3"
                          placeholder="Kampanya hakkında açıklama...">{{ old('Description', $campaign->Description ?? '') }}</textarea>
                @error('Description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- İndirim Bilgileri -->
            <hr class="my-4">
            <h6 class="mb-3">İndirim Detayları</h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">İndirim Türü <span class="text-danger">*</span></label>
                        <select name="DiscountType" 
                                class="form-select @error('DiscountType') is-invalid @enderror" 
                                required
                                id="discountType">
                            @foreach(['yuzde'=>'Yüzde İndirim (%)', 'sabit'=>'Sabit İndirim (₺)', 'ucretsiz_kargo'=>'Ücretsiz Kargo', 'paket'=>'Paket İndirim'] as $key=>$label)
                                <option value="{{ $key }}" 
                                        {{ (old('DiscountType', $campaign->DiscountType ?? '')==$key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('DiscountType')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">İndirim Değeri <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" 
                                   name="DiscountValue" 
                                   class="form-control @error('DiscountValue') is-invalid @enderror" 
                                   value="{{ old('DiscountValue', $campaign->DiscountValue ?? 0) }}" 
                                   min="0"
                                   step="0.01"
                                   required
                                   placeholder="Örn: 10 veya 50"
                                   id="discountValue">
                            <span class="input-group-text" id="discountUnit">₺</span>
                        </div>
                        @error('DiscountValue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Yüzde için: 10 (=%10), Sabit için: 50.00 (=50₺)</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Minimum Sepet Tutarı</label>
                        <div class="input-group">
                            <input type="number" 
                                   name="MinCartTotal" 
                                   class="form-control @error('MinCartTotal') is-invalid @enderror" 
                                   value="{{ old('MinCartTotal', $campaign->MinCartTotal ?? '') }}" 
                                   min="0"
                                   step="0.01"
                                   placeholder="0.00">
                            <span class="input-group-text">₺</span>
                        </div>
                        @error('MinCartTotal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kampanya için gerekli min. tutar</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Toplam Kullanım Limiti</label>
                        <input type="number" 
                               name="UsageLimit" 
                               class="form-control @error('UsageLimit') is-invalid @enderror" 
                               value="{{ old('UsageLimit', $campaign->UsageLimit ?? '') }}" 
                               min="1"
                               placeholder="Sınırsız">
                        @error('UsageLimit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kampanyanın kaç kez kullanılabileceği</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Başına Limit</label>
                        <input type="number" 
                               name="PerUserLimit" 
                               class="form-control @error('PerUserLimit') is-invalid @enderror" 
                               value="{{ old('PerUserLimit', $campaign->PerUserLimit ?? '') }}" 
                               min="1"
                               placeholder="Sınırsız">
                        @error('PerUserLimit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Her kullanıcı kaç kez kullanabilir</small>
                    </div>
                </div>
            </div>

            <!-- Tarih Bilgileri -->
            <hr class="my-4">
            <h6 class="mb-3">Kampanya Süresi</h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" 
                               name="StartDate" 
                               class="form-control @error('StartDate') is-invalid @enderror" 
                               value="{{ old('StartDate', $campaign->StartDate ?? '') }}" 
                               required>
                        @error('StartDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Bitiş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" 
                               name="EndDate" 
                               class="form-control @error('EndDate') is-invalid @enderror" 
                               value="{{ old('EndDate', $campaign->EndDate ?? '') }}" 
                               required>
                        @error('EndDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kampanya Kapsamı -->
            <hr class="my-4">
            <h6 class="mb-3">Kampanya Kapsamı</h6>
            <p class="text-muted small">Kampanyanın hangi kategoriler, alt kategoriler veya ürünler için geçerli olacağını seçin. Hiçbir şey seçilmezse tüm ürünlerde geçerli olur.</p>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-category"></i> Kategoriler
                        </label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background: #f8f9fa;">
                            @forelse($categories as $category)
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->CategoryId }}" 
                                           class="form-check-input"
                                           id="cat_{{ $category->CategoryId }}"
                                           {{ isset($campaign) && $campaign->categories->contains($category->CategoryId) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat_{{ $category->CategoryId }}">
                                        {{ $category->CategoryName }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Kategori bulunamadı</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-sitemap"></i> Alt Kategoriler
                        </label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background: #f8f9fa;">
                            @forelse($subCategories as $sub)
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="subCategories[]" 
                                           value="{{ $sub->SubCategoryId }}" 
                                           class="form-check-input"
                                           id="sub_{{ $sub->SubCategoryId }}"
                                           {{ isset($campaign) && $campaign->subCategories->contains($sub->SubCategoryId) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sub_{{ $sub->SubCategoryId }}">
                                        {{ $sub->SubCategoryName }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Alt kategori bulunamadı</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-box"></i> Ürünler
                        </label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background: #f8f9fa;">
                            @forelse($products as $product)
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="products[]" 
                                           value="{{ $product->ProductId }}" 
                                           class="form-check-input"
                                           id="prod_{{ $product->ProductId }}"
                                           {{ isset($campaign) && $campaign->products->contains($product->ProductId) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="prod_{{ $product->ProductId }}">
                                        {{ $product->Ad }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Ürün bulunamadı</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Butonları -->
            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bx bx-check"></i> {{ isset($campaign) ? 'Güncelle' : 'Kaydet' }}
                </button>
                <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary btn-lg">
                    <i class="bx bx-x"></i> İptal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// İndirim türüne göre birimi değiştir
document.getElementById('discountType').addEventListener('change', function() {
    const unit = document.getElementById('discountUnit');
    if (this.value === 'yuzde') {
        unit.textContent = '%';
    } else if (this.value === 'sabit') {
        unit.textContent = '₺';
    } else {
        unit.textContent = '-';
    }
});

// Sayfa yüklendiğinde birimi ayarla
window.addEventListener('DOMContentLoaded', function() {
    const discountType = document.getElementById('discountType').value;
    const unit = document.getElementById('discountUnit');
    if (discountType === 'yuzde') {
        unit.textContent = '%';
    } else if (discountType === 'sabit') {
        unit.textContent = '₺';
    } else {
        unit.textContent = '-';
    }
});
</script>
@endsection