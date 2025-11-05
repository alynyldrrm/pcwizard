<!-- Cart Modal -->
<div id="cartModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);" class="text-white p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Sepetim
                </h2>
                <button id="closeCart" class="text-white hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div id="cart-items" class="space-y-4 max-h-96 overflow-y-auto mb-6">
                <div class="text-center py-12 text-gray-500" id="empty-cart">
                    <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                    <p class="text-xl mb-2">Sepetiniz boş</p>
                    <p class="text-sm">Ürün eklemek için alışverişe başlayın!</p>
                </div>
            </div>
            
            <div class="border-t pt-6">
                <div class="flex justify-between items-center text-xl font-bold mb-6">
                    <span>Toplam:</span>
                    <span id="cart-total" class="text-blue-600">0 ₺</span>
                </div>
                
                <div class="space-y-3">
                    @auth
                        <button id="completeOrder" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);" class="w-full text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                            <i class="fas fa-credit-card mr-2"></i>
                            Siparişi Tamamla
                        </button>
                        <button id="startWizard" class="w-full bg-white border-2 border-blue-600 text-blue-600 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-all">
                            <i class="fas fa-magic mr-2"></i>
                            PC Toplama Sihirbazı
                        </button>
                    @else
                        <a href="{{ route('login') }}" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);" class="block w-full text-white py-3 rounded-lg font-semibold text-center hover:shadow-lg transition-all">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sipariş Vermek İçin Giriş Yapın
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);" class="text-white p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold">Ürün Detayları</h2>
                <button id="closeProductModal" class="text-white hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="product-details-content">
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <p class="mt-4 text-gray-600">Detaylar yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>