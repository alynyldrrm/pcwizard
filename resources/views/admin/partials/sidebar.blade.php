<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <i class="bx bx-cog bx-sm"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">PCWizard</span>
        </a>
        
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    
    <div class="menu-inner-shadow"></div>
    
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        
        <!-- Kullanıcılar -->
        <li class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <a href="{{ route('admin.users') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account">Kullanıcılar</div>
            </a>
        </li>
        
        <!-- Kategoriler -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Ürün Yönetimi</span>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Categories">Kategoriler</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.subcategories*') ? 'active' : '' }}">
            <a href="{{ route('admin.subcategories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-sitemap"></i>
                <div data-i18n="Subcategories">Alt Kategoriler</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.campaigns*') ? 'active' : '' }}">
            <a href="{{ route('admin.campaigns.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-sitemap"></i>
                <div data-i18n="Campaign">Kampanyalar</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.kriterler*') ? 'active' : '' }}">
            <a href="{{ route('admin.kriterler.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Criteria">Kriterler</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Products">Ürünler</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.criteria_compatibilities*') ? 'active' : '' }}">
            <a href="{{ route('admin.criteria_compatibilities.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-network-chart"></i>
                <div data-i18n="Compatibilities">Uyumluluklar</div>
            </a>
        </li>
        
        <!-- Stok Yönetimi -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Stok & Sipariş</span>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.stock_logs.index') ? 'active' : '' }}">
            <a href="{{ route('admin.stock_logs.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Stock Logs">Stok Kayıtları</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.stock_logs.create') ? 'active' : '' }}">
            <a href="{{ route('admin.stock_logs.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-plus"></i>
                <div data-i18n="Add Stock">Stok Ekle</div>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Orders">Siparişler</div>
            </a>
        </li>
        
        <!-- İletişim -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">İletişim</span>
        </li>
        
        <li class="menu-item {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
            <a href="{{ route('admin.messages.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div data-i18n="Messages">Mesajlar</div>
            </a>
        </li>
        
        <!-- Raporlar -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Raporlama</span>
        </li>
        
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div data-i18n="Reports">Raporlar</div>
            </a>
        </li>
    </ul>
</aside>