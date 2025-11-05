<nav class="border-t">
    <div class="flex items-center">
        <div class="category-menu relative">
            <button id="categoriesBtn" class="flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 hover:bg-blue-700">
                <i class="fas fa-bars"></i>
                <span>Tüm Kategoriler</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>
            
            <div id="categoryDropdown" class="category-dropdown hidden">
                @if(isset($categories))
                    @foreach($categories as $category)
                        <div class="group relative">
                            <a href="{{ route('kategori.urunleri', ['categoryId' => $category->CategoryId]) }}" class="flex items-center justify-between p-3 hover:bg-gray-50 border-b border-gray-100">
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
                                <div class="absolute left-full top-0 w-64 bg-white border border-gray-200 shadow-lg hidden group-hover:block z-20">
                                    @foreach($category->subCategories as $subCategory)
                                        <a href="{{ route('altkategori.urunleri', ['subCategoryId' => $subCategory->SubCategoryId]) }}" class="subcategory-item flex items-center space-x-3 p-3 border-b border-gray-100 hover:bg-gray-50">
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
                <a href="{{ route('home') }}" class="py-3 text-gray-700 hover:text-blue-600 font-medium">Ana Sayfa</a>
                <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium">Kampanyalar</a>
                <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium">İndirimli Ürünler</a>
                <a href="#" class="py-3 text-gray-700 hover:text-blue-600 font-medium">PC Toplama</a>
                <a href="#contact" class="py-3 text-gray-700 hover:text-blue-600 font-medium">İletişim</a>
            </div>
            
            <div class="flex items-center space-x-2">
                <i class="fas fa-truck text-green-600"></i>
                <span class="text-sm text-green-600 font-medium">Ücretsiz Kargo</span>
            </div>
        </div>
    </div>
</nav>