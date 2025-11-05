<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Fonksiyonu - Düzeltilmiş</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<!-- Arama Formu -->
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex-1 max-w-2xl mx-auto">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Ürün, marka, kategori veya model ara..." 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button id="searchBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-search"></i>
            </button>
            <!-- Live arama sonuçları -->
            <div id="searchResults" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto">
                <!-- Buraya AJAX sonuçları gelecek -->
            </div>
        </div>
    </div>
</div>

<!-- Debug Bilgileri -->
<div id="debugInfo" class="max-w-7xl mx-auto px-4 py-4 bg-yellow-100 border border-yellow-300 rounded-lg mb-4 hidden">
    <h3 class="font-bold text-yellow-800">Debug Bilgileri:</h3>
    <div id="debugContent" class="text-sm text-yellow-700 mt-2"></div>
</div>

<!-- Arama Sonuçları Bölümü -->
<div id="searchResultsSection" class="max-w-7xl mx-auto px-4 py-8 hidden">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Arama Sonuçları</h2>
        <p id="searchInfo" class="text-gray-600"></p>
    </div>
    <div id="searchResultsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Ürün kartları buraya gelecek -->
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
        <p class="text-gray-600">Aranıyor...</p>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Sayfa yüklendi, arama fonksiyonu hazırlanıyor...');
    
    let searchTimeout;
    
    // CSRF token'ı ayarla
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Debug fonksiyonu
    function showDebugInfo(message, data = null) {
        console.log('DEBUG:', message, data);
        let content = `<strong>${new Date().toLocaleTimeString()}:</strong> ${message}`;
        if (data) {
            content += `<br><pre class="mt-1 bg-yellow-200 p-2 rounded text-xs overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>`;
        }
        $('#debugContent').html(content);
        $('#debugInfo').removeClass('hidden');
    }
    
    // Arama inputu değiştiğinde (live search)
    $('#searchInput').on('input', function() {
        const query = $(this).val().trim();
        showDebugInfo(`Input değişti: "${query}"`);
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                showDebugInfo(`Live arama başlatılıyor: "${query}"`);
                performLiveSearch(query);
            }, 300);
        } else {
            $('#searchResults').addClass('hidden');
            showDebugInfo('Query çok kısa, dropdown gizlendi');
        }
    });

    // Enter tuşu ile arama
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            showDebugInfo('Enter tuşuna basıldı');
            performFullSearch();
        }
    });

    // Arama butonuna tıklama
    $('#searchBtn').click(function() {
        showDebugInfo('Arama butonuna tıklandı');
        performFullSearch();
    });

    // Sayfa dışına tıklayınca dropdown'u kapat
    $(document).click(function(e) {
        if (!$(e.target).closest('.relative').length) {
            $('#searchResults').addClass('hidden');
        }
    });

    // Live arama fonksiyonu (dropdown için)
    function performLiveSearch(query) {
        showDebugInfo('Live arama AJAX isteği gönderiliyor', { url: '/search/live', query: query });
        
        $.ajax({
            url: '/search/live',
            method: 'GET',
            data: { q: query },
            beforeSend: function() {
                showDebugInfo('AJAX isteği gönderiliyor...');
            },
            success: function(response) {
                showDebugInfo('Live arama başarılı', response);
                displayLiveResults(response);
            },
            error: function(xhr, status, error) {
                const errorInfo = {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                };
                showDebugInfo('Live arama hatası!', errorInfo);
                console.error('Live arama hatası:', errorInfo);
                
                // Hata mesajını göster
                $('#searchResults').html(`
                    <div class="p-4 text-center text-red-500">
                        <i class="fas fa-exclamation-triangle mb-2"></i>
                        <div>Arama hatası: ${xhr.status} ${xhr.statusText}</div>
                        <div class="text-xs mt-1">URL: /search/live</div>
                        ${xhr.responseText ? `<div class="text-xs mt-1 bg-red-50 p-2 rounded">${xhr.responseText}</div>` : ''}
                    </div>
                `).removeClass('hidden');
            }
        });
    }

    // Tam arama fonksiyonu (sayfa yönlendirme)
    function performFullSearch() {
        const query = $('#searchInput').val().trim();
        showDebugInfo('Tam arama başlatılıyor', { query: query });
        
        if (query.length < 2) {
            showToast('En az 2 karakter giriniz', 'warning');
            return;
        }

        $('#loadingSpinner').removeClass('hidden');

        $.ajax({
            url: '/search',
            method: 'GET',
            data: { q: query },
            beforeSend: function() {
                showDebugInfo('Tam arama AJAX isteği gönderiliyor...');
            },
            success: function(response) {
                showDebugInfo('Tam arama başarılı', response);
                $('#loadingSpinner').addClass('hidden');
                displayFullResults(response, query);
                $('#searchResults').addClass('hidden');
            },
            error: function(xhr, status, error) {
                const errorInfo = {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                };
                showDebugInfo('Tam arama hatası!', errorInfo);
                $('#loadingSpinner').addClass('hidden');
                showToast(`Arama hatası: ${xhr.status} ${xhr.statusText}`, 'error');
                console.error('Tam arama hatası:', errorInfo);
            }
        });
    }

    // Live arama sonuçlarını göster (dropdown)
    function displayLiveResults(results) {
        let html = '';
        
        if (!results) {
            html = '<div class="p-4 text-center text-gray-500">Geçersiz yanıt</div>';
        } else if (results.products && results.products.length > 0) {
            html += '<div class="p-2 font-semibold text-gray-700 bg-gray-50">Ürünler</div>';
            results.products.slice(0, 5).forEach(function(product) {
                const imageUrl = product.Resim ? '/' + product.Resim : 'https://via.placeholder.com/40x40';
                const price = parseFloat(product.Fiyat || 0).toLocaleString('tr-TR');
                
                html += `
                    <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b search-item" data-type="product" data-id="${product.ProductId}">
                        <img src="${imageUrl}" alt="${product.Ad}" class="w-10 h-10 object-contain rounded mr-3" onerror="this.src='https://via.placeholder.com/40x40'">
                        <div class="flex-1">
                            <div class="font-medium text-sm">${product.Marka || ''} ${product.Model || ''}</div>
                            <div class="text-xs text-gray-600">${product.Ad || 'Ürün Adı Yok'}</div>
                            <div class="text-xs text-blue-600 font-semibold">${price} ₺</div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (results.categories && results.categories.length > 0) {
            html += '<div class="p-2 font-semibold text-gray-700 bg-gray-50 border-t">Kategoriler</div>';
            results.categories.slice(0, 3).forEach(function(category) {
                html += `
                    <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b search-item" data-type="category" data-id="${category.CategoryId}">
                        <i class="fas fa-folder text-blue-600 mr-3"></i>
                        <div class="font-medium text-sm">${category.CategoryName}</div>
                    </div>
                `;
            });
        }
        
        if (results.brands && results.brands.length > 0) {
            html += '<div class="p-2 font-semibold text-gray-700 bg-gray-50 border-t">Markalar</div>';
            results.brands.slice(0, 3).forEach(function(brand) {
                html += `
                    <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b search-item" data-type="brand" data-brand="${brand}">
                        <i class="fas fa-tag text-green-600 mr-3"></i>
                        <div class="font-medium text-sm">${brand}</div>
                    </div>
                `;
            });
        }
        
        if (html === '') {
            html = '<div class="p-4 text-center text-gray-500">Sonuç bulunamadı</div>';
        }
        
        $('#searchResults').html(html).removeClass('hidden');
    }

    // Tam arama sonuçlarını göster
    function displayFullResults(results, query) {
        let html = '';
        const totalCount = (results.products ? results.products.length : 0) + 
                          (results.categories ? results.categories.length : 0);
        
        $('#searchInfo').text(`"${query}" için ${totalCount} sonuç bulundu`);
        
        // Kategoriler
        if (results.categories && results.categories.length > 0) {
            results.categories.forEach(function(category) {
                html += `
                    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <i class="fas fa-folder text-2xl text-blue-600 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">${category.CategoryName}</h3>
                                <p class="text-sm text-gray-600">Kategori</p>
                                <a href="/urunler?category=${category.CategoryId}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Kategoriye Git →
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        // Ürünler
        if (results.products && results.products.length > 0) {
            results.products.forEach(function(product) {
                const imageUrl = product.Resim ? '/' + product.Resim : 'https://via.placeholder.com/200x200';
                const price = parseFloat(product.Fiyat || 0).toLocaleString('tr-TR');
                
                html += `
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow product-card cursor-pointer" data-product-id="${product.ProductId}">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-100 p-4">
                            <img src="${imageUrl}" alt="${product.Ad}" class="w-full h-48 object-contain" onerror="this.src='https://via.placeholder.com/200x200'">
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-blue-600 font-medium mb-1">${product.Marka || ''}</div>
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">${product.Ad || 'Ürün Adı Yok'}</h3>
                            <p class="text-sm text-gray-600 mb-3">${product.Model || ''}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-blue-600">${price} ₺</span>
                                <div class="flex items-center space-x-2">
                                    <button class="add-to-cart-btn bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700" data-product-id="${product.ProductId}">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (html === '') {
            html = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Sonuç Bulunamadı</h3>
                    <p class="text-gray-500">"${query}" için herhangi bir sonuç bulunamadı.</p>
                    <p class="text-gray-500 mt-2">Farklı bir arama terimi deneyin.</p>
                </div>
            `;
        }
        
        $('#searchResultsGrid').html(html);
        $('#searchResultsSection').removeClass('hidden');
        
        // Sonuçlara scroll
        $('html, body').animate({
            scrollTop: $('#searchResultsSection').offset().top - 100
        }, 500);
    }

    // Dropdown'dan öğe seçme
    $(document).on('click', '.search-item', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const brand = $(this).data('brand');
        
        if (type === 'product') {
            showProductDetails(id);
        } else if (type === 'category') {
            window.location.href = `/urunler?category=${id}`;
        } else if (type === 'brand') {
            window.location.href = `/urunler?brand=${encodeURIComponent(brand)}`;
        }
        
        $('#searchResults').addClass('hidden');
    });

    // Toast bildirimi
    function showToast(message, type = 'info') {
        const bgColor = type === 'success' ? 'bg-green-500' : 
                      type === 'error' ? 'bg-red-500' : 
                      type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                   type === 'error' ? 'fa-exclamation-circle' : 
                   type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';

        const toast = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3 transform translate-x-full opacity-0 transition-all duration-300">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.removeClass('translate-x-full opacity-0');
        }, 100);

        setTimeout(() => {
            toast.addClass('translate-x-full opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Ürün detayları (placeholder)
    function showProductDetails(productId) {
        showToast('Ürün detayları yükleniyor...', 'info');
    }

    // Test butonu
    $('<button class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600" id="testBtn">Test Arama</button>').appendTo('body');
    
    $('#testBtn').click(function() {
        $('#searchInput').val('test').trigger('input');
        setTimeout(() => {
            performFullSearch();
        }, 1000);
    });
});
</script>

</body>
</html>