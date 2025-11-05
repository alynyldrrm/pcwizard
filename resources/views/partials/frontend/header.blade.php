<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Avantaj Bilişim" class="h-12 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="h-12 flex items-center" style="display: none;">
                    <span class="text-2xl font-bold text-blue-600">Avantaj</span>
                    <span class="text-2xl font-bold text-red-600">Bilişim</span>
                </div>
            </div>

            <div class="flex-1 max-w-2xl mx-8">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Ürün, marka, kategori veya model ara..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button id="searchBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    <div id="searchResults" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto"></div>
                </div>
            </div>

        <div class="flex items-center space-x-4">
    <button class="relative p-2 text-gray-600 hover:text-red-600 transition-colors">
        <i class="fas fa-heart text-xl"></i>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
    </button>
    
    <!-- TEK SEPET BUTONU - Bu çalışacak -->
    <button id="cartIcon" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span id="cartBadge" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
    </button>
    
    @auth
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors">
            <i class="fas fa-user-circle text-xl"></i>
            <span class="hidden md:block">Hesabım</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
            Giriş Yap
        </a>
    @endauth
</div>
        </div>

        <nav class="border-t">
            <div class="flex items-center">
                <div class="category-menu relative">
                    <button id="categoriesBtn" class="flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 hover:bg-blue-700 transition-colors">
                        <i class="fas fa-bars"></i>
                        <span>Tüm Kategoriler</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    
                    <div id="categoryDropdown" class="category-dropdown hidden">
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <div class="group">
                                    <a href="{{ route('urunler', ['category' => $category->CategoryId]) }}" class="flex items-center justify-between p-3 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            @if($category->CategoryImage)
                                                <img src="{{ asset($category->CategoryImage) }}" alt="{{ $category->CategoryName }}" class="w-6 h-6 object-contain">
                                            @else
                                                <i class="fas fa-microchip text-blue-600"></i>
                                            @endif
                                            <span>{{ $category->CategoryName }}</span>
                                        </div>
                                        @if($category->subCategories->count() > 0)
                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                        @endif
                                    </a>
                                    
                                    @if($category->subCategories->count() > 0)
                                        <div class="absolute left-full top-0 w-64 bg-white border border-gray-200 shadow-lg hidden group-hover:block">
                                            @foreach($category->subCategories as $subCategory)
                                                <a href="{{ route('urunler', ['subcategory' => $subCategory->SubCategoryId]) }}" class="subcategory-item flex items-center space-x-3 p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                    @if($subCategory->SubCategoryImage)
                                                        <img src="{{ asset($subCategory->SubCategoryImage) }}" alt="{{ $subCategory->SubCategoryName }}" class="w-5 h-5 object-contain">
                                                    @endif
                                                    <span class="text-sm">{{ $subCategory->SubCategoryName }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex-1 flex items-center justify-between">
                    <div class="flex space-x-8 ml-8">
                        <a href="/" class="py-3 text-gray-700 hover:text-blue-600 font-medium transition-colors">Ana Sayfa</a>
                        <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium transition-colors">Kampanyalar</a>
                        <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium transition-colors">İndirimli Ürünler</a>
                        <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium transition-colors">PC Toplama</a>
                        <a href="#contact" class="py-3 text-gray-700 hover:text-blue-600 font-medium transition-colors">İletişim</a>
                        <a href="#" id="cartIcon" class="relative">
             
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-truck text-green-600"></i>
                        <span class="text-sm text-green-600 font-medium">Ücretsiz Kargo</span>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<style>
    .category-menu { position: relative; }
    .category-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-top: none;
        z-index: 50;
        max-height: 400px;
        overflow-y: auto;
    }
</style>