
<x-app-layout>
    <x-slot name="header">
    <div class="bg-gradient-to-r from-blue-700 to-cyan-800 -mx-8 -mt-6 px-8 pt-6 pb-4">
        <h2 class="font-bold text-3xl text-white leading-tight flex items-center gap-3">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-light text-blue-100">Hoş Geldiniz</div>
                <div class="text-3xl font-bold">{{ auth()->user()->name }}</div>
            </div>
        </h2>
    </div>
</x-slot>

<div class="py-6 bg-blue-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-cyan-900 rounded-2xl shadow-xl overflow-hidden">
                <div class="relative p-8">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full transform translate-x-32 -translate-y-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full transform -translate-x-24 translate-y-24"></div>
                    
                    <div class="relative flex flex-col lg:flex-row items-center justify-between">
                        <div class="flex items-center gap-6 text-white mb-6 lg:mb-0">
                            <div class="bg-white bg-opacity-15 p-4 rounded-xl backdrop-blur-sm border border-white border-opacity-20">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold mb-1">PC Toplama Sihirbazı</h3>
                                <p class="text-blue-100 text-sm">Akıllı uyumluluk kontrolü ile profesyonel PC toplama</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('wizard.index') }}" class="bg-white text-blue-700 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl text-center">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Sihirbazı Başlat
                            </a>
                            <a href="#configurations" class="bg-white bg-opacity-10 text-white px-6 py-3 rounded-xl font-semibold hover:bg-opacity-20 transition-all duration-300 backdrop-blur-sm border border-white border-opacity-20 text-center">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Konfigürasyonlar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Toplam Sipariş</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2" id="total-orders">0</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Kayıtlı PC</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2" id="total-configs">0</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Toplam Harcama</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2" id="total-spent">0 ₺</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                Siparişlerim
                            </h3>
                            <button onclick="loadOrders()" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1 px-3 py-1 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Yenile
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div id="orders-container">
                            <div class="text-center py-12 text-gray-500">
                                <div class="bg-blue-100 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Henüz sipariş bulunmuyor</h4>
                                <p class="text-sm text-gray-500 mb-6">İlk siparişinizi vermek için PC Sihirbazını kullanın</p>
                                <a href="{{ route('wizard.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Alışverişe Başla
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Son Aktiviteler
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div id="activities-container">
                            <div class="text-center py-8 text-gray-500">
                                <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-900 mb-1">Henüz aktivite bulunmuyor</p>
                                <p class="text-sm text-gray-500">Sihirbazı kullanarak aktivite oluşturmaya başlayın</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="configurations" class="space-y-8">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                Konfigürasyonlarım
                            </h3>
                            <button onclick="loadConfigurations()" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1 px-3 py-1 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Yenile
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div id="configurations-container">
                            <div class="text-center py-8 text-gray-500">
                                <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-900 mb-1">Henüz kayıtlı konfigürasyon yok</p>
                                <p class="text-sm text-gray-500">Sihirbaz ile PC yapılandırması oluşturun</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            Hızlı Eylemler
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        <a href="{{ route('wizard.index') }}" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            PC Sihirbazını Başlat
                        </a>
                        <button onclick="$('#cartModal').removeClass('hidden')" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            Sepeti Görüntüle
                        </button>
                        <a href="/" class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Mağazaya Git
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="orderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-cyan-800 text-white p-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Sipariş Detayları</h2>
                </div>
                <button onclick="$('#orderModal').addClass('hidden')" class="text-white hover:text-gray-300 transition-colors duration-200 p-2 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-96">
            <div id="order-details-content">
                </div>
        </div>
    </div>
</div>


<div id="cartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-700 text-white p-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="text-2xl mr-2">🛒</span>
                    <h2 class="text-2xl font-bold">Sepetim</h2>
                </div>
                <button onclick="$('#cartModal').addClass('hidden')" class="text-white hover:text-gray-300 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div id="cart-items-container">
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2 text-blue-500">🛒</div>
                    <p class="text-lg font-semibold text-gray-900">Sepetiniz boş</p>
                    <p class="text-sm">Alışverişe başlamak için ürün ekleyin</p>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Sayfa yüklendiğinde verileri getir
    loadDashboardData();
});

// Dashboard verilerini yükle
window.loadDashboardData = function() {
    loadOrders();
    loadConfigurations();
    loadStatistics();
    loadRecentActivities();
};

// Siparişleri yükle
window.loadOrders = function() {
    $.get('/api/user/orders', function(response) {
        if (response.orders && response.orders.length > 0) {
            displayOrders(response.orders);
        } else {
            $('#orders-container').html(`
                <div class="text-center py-12 text-gray-500">
                    <div class="text-6xl mb-4 opacity-50">📦</div>
                    <p class="text-lg">Henüz sipariş bulunmuyor</p>
                    <p class="text-sm mt-2">İlk siparişinizi vermek için sihirbazı kullanın</p>
                    <a href="{{ route('wizard.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-all duration-200">
                        Alışverişe Başla
                    </a>
                </div>
            `);
        }
    }).fail(function() {
        $('#orders-container').html(`
            <div class="text-center py-8 text-red-500">
                <p>Siparişler yüklenirken hata oluştu</p>
                <button onclick="loadOrders()" class="mt-2 text-blue-600 hover:text-blue-800">Tekrar Dene</button>
            </div>
        `);
    });
};

// Siparişleri göster
window.displayOrders = function(orders) {
    let html = '';
    orders.slice(0, 3).forEach(function(order) {
        const statusColor = getStatusColor(order.OrderStatus);
        const statusText = getStatusText(order.OrderStatus);
        
        html += `
            <div class="p-4 border border-gray-200 rounded-xl hover:shadow-lg transition-all duration-200 cursor-pointer" onclick="showOrderDetails(${order.OrderId})">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h5 class="font-bold text-gray-800">#${order.OrderNumber}</h5>
                        <p class="text-sm text-gray-600">${new Date(order.OrderDate).toLocaleDateString('tr-TR')}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColor}">
                        ${statusText}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-green-600">${parseFloat(order.TotalAmount).toLocaleString('tr-TR')} ₺</span>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detayları Gör →</button>
                </div>
            </div>
        `;
    });
    
    $('#orders-container').html(html);
};

// Konfigürasyonları yükle
window.loadConfigurations = function() {
    $.get('/wizard/configurations', function(response) {
        if (response.configurations && response.configurations.length > 0) {
            displayConfigurations(response.configurations);
        } else {
            $('#configurations-container').html(`
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-3 opacity-50">🖥️</div>
                    <p class="text-sm">Henüz kayıtlı konfigürasyon yok</p>
                    <p class="text-xs text-gray-400 mt-1">Sihirbaz ile PC yapılandırması oluşturun</p>
                </div>
            `);
        }
    }).fail(function() {
        $('#configurations-container').html(`
            <div class="text-center py-8 text-red-500">
                <p class="text-sm">Konfigürasyonlar yüklenemedi</p>
                <button onclick="loadConfigurations()" class="mt-2 text-blue-600 hover:text-blue-800 text-xs">Tekrar Dene</button>
            </div>
        `);
    });
};

// Konfigürasyonları göster
window.displayConfigurations = function(configurations) {
    let html = '';
    configurations.slice(0, 4).forEach(function(config) {
        const itemCount = config.items ? config.items.length : 0;
        const createdDate = config.CreatedDate ? new Date(config.CreatedDate).toLocaleDateString('tr-TR') : 'Tarih yok';
        
        html += `
            <div class="p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-200">
                <div class="flex justify-between items-start mb-3">
                    <h5 class="font-bold text-gray-800 text-sm">${config.Name || 'Konfigürasyon'}</h5>
                    <div class="flex space-x-1">
                        <button onclick="loadConfiguration(${config.BuildId})" class="text-blue-600 hover:text-blue-800 p-1" title="Yükle">📥</button>
                        <button onclick="deleteConfiguration(${config.BuildId})" class="text-red-600 hover:text-red-800 p-1" title="Sil">🗑️</button>
                    </div>
                </div>
                <div class="text-xs text-gray-600 mb-2">
                    ${itemCount} ürün • ${parseFloat(config.TotalPrice).toLocaleString('tr-TR')} ₺
                </div>
                <div class="text-xs text-gray-500">${createdDate}</div>
            </div>
        `;
    });
    
    $('#configurations-container').html(html);
};

// İstatistikleri yükle
window.loadStatistics = function() {
    Promise.all([
        $.get('/api/user/orders'),
        $.get('/wizard/configurations')
    ]).then(([ordersResponse, configsResponse]) => {
        const orders = ordersResponse.orders || [];
        const configs = configsResponse.configurations || [];
        let totalSpent = 0;
        orders.forEach(order => {
            if (order.OrderStatus === 'Paid' || order.OrderStatus === 'Completed') {
                totalSpent += parseFloat(order.TotalAmount);
            }
        });
        $('#total-orders').text(orders.length);
        $('#total-configs').text(configs.length);
        $('#total-spent').text(totalSpent.toLocaleString('tr-TR') + ' ₺');
    }).catch(() => {});
};

// Son aktiviteleri yükle
window.loadRecentActivities = function() {
    setTimeout(function() {
        $('#activities-container').html(`
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-3 opacity-50">⏰</div>
                <p>Henüz aktivite bulunmuyor</p>
                <p class="text-sm text-gray-400 mt-1">Sihirbazı kullanarak aktivite oluşturmaya başlayın</p>
            </div>
        `);
    }, 1000);
};

// Sipariş detayları
window.showOrderDetails = function(orderId) {
    $('#orderModal').removeClass('hidden');
    $('#order-details-content').html(`
        <div class="relative p-4">
            <!-- Kapatma butonu -->
            <button onclick="$('#orderModal').addClass('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>

            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Sipariş detayları yükleniyor...</p>
            </div>
        </div>
    `);

    // Sipariş detaylarını getir
    $.get(`/api/orders/${orderId}`, function(order) {
        displayOrderDetails(order);
    }).fail(function() {
        $('#order-details-content').html('<p class="text-center text-red-500 py-4">Sipariş detayları yüklenemedi.</p>');
    });
};


// Sipariş detaylarını göster
window.displayOrderDetails = function(order) {
    const statusColor = getStatusColor(order.OrderStatus);
    const statusText = getStatusText(order.OrderStatus);
    let itemsHtml = '';
    if (order.items) {
        order.items.forEach(item => {
            const imageUrl = item.ProductImage ? '/' + item.ProductImage : '/images/products/default.jpg';
            itemsHtml += `
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <img src="${imageUrl}" alt="${item.ProductName}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h5 class="font-semibold">${item.ProductBrand} ${item.ProductModel}</h5>
                        <p class="text-sm text-gray-600">${item.ProductName}</p>
                        <div class="flex justify-between mt-2">
                            <span>Adet: ${item.Quantity}</span>
                            <span class="font-bold text-green-600">${parseFloat(item.TotalPrice).toLocaleString('tr-TR')} ₺</span>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    $('#order-details-content').html(`
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-700">Sipariş Numarası</h4>
                    <p class="text-lg">#${order.OrderNumber}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700">Durum</h4>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold ${statusColor}">${statusText}</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700">Sipariş Tarihi</h4>
                    <p>${new Date(order.OrderDate).toLocaleDateString('tr-TR')}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700">Toplam Tutar</h4>
                    <p class="text-xl font-bold text-green-600">${parseFloat(order.TotalAmount).toLocaleString('tr-TR')} ₺</p>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700 mb-4">Sipariş Edilen Ürünler</h4>
                <div class="space-y-3">${itemsHtml}</div>
            </div>
        </div>
    `);
};

// Konfig yükle
window.loadConfiguration = function(configId) {
    if (confirm('Bu konfigürasyonu yüklemek istediğinizden emin misiniz?')) {
        $.post('/wizard/load-configuration', { config_id: configId }, function(response) {
            if (response.success) window.location.href = '/wizard';
        });
    }
};

// Konfig sil
window.deleteConfiguration = function(configId) {
    if (confirm('Bu konfigürasyonu silmek istediğinizden emin misiniz?')) {
        $.post('/wizard/delete-configuration', { config_id: configId }, function(response) {
            if (response.success) {
                loadConfigurations();
                loadStatistics();
                showToast('Konfigürasyon silindi', 'success');
            } else {
                showToast('Konfigürasyon silinemedi', 'error');
            }
        }).fail(function() {
            showToast('Bir hata oluştu', 'error');
        });
    }
};

// Durum renk
window.getStatusColor = function(status) {
    switch(status) {
        case 'Paid':
        case 'Completed': return 'bg-green-100 text-green-800';
        case 'Pending': return 'bg-yellow-100 text-yellow-800';
        case 'Cancelled': return 'bg-red-100 text-red-800';
        case 'Processing': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Durum metin
window.getStatusText = function(status) {
    switch(status) {
        case 'Paid': return 'Ödendi';
        case 'Completed': return 'Tamamlandı';
        case 'Pending': return 'Beklemede';
        case 'Cancelled': return 'İptal Edildi';
        case 'Processing': return 'İşleniyor';
        default: return status;
    }
};

// Toast
window.showToast = function(message, type='info') {
    const bgColor = type==='success'?'bg-green-500':
                    type==='error'?'bg-red-500':
                    type==='warning'?'bg-yellow-500':'bg-blue-500';
    const icon = type==='success'?'✓':
                 type==='error'?'✗':
                 type==='warning'?'⚠':'ℹ';

    const toast = $(`
        <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center space-x-2 transform translate-x-full opacity-0 transition-all duration-300">
            <span class="text-lg">${icon}</span>
            <span>${message}</span>
        </div>
    `);
    $('body').append(toast);
    setTimeout(()=>{ toast.removeClass('translate-x-full opacity-0'); }, 100);
    setTimeout(()=>{ toast.addClass('translate-x-full opacity-0'); setTimeout(()=>toast.remove(),300); },3000);
};
</script>
@endpush

</x-app-layout>