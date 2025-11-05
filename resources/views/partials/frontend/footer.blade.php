<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center mb-6">
                    <span class="text-2xl font-bold text-blue-400">Avantaj</span>
                    <span class="text-2xl font-bold text-red-400">Bilişim</span>
                </div>
                <p class="text-gray-300 mb-4">Türkiye'nin güvenilir teknoloji partneri. En kaliteli ürünler, en uygun fiyatlar.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-6">Kategoriler</h3>
                <ul class="space-y-3 text-gray-300">
                    @if(isset($categories))
                        @foreach($categories->take(6) as $category)
                            <li><a href="{{ route('urunler', ['category' => $category->CategoryId]) }}" class="hover:text-white transition-colors">{{ $category->CategoryName }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-6">Müşteri Hizmetleri</h3>
                <ul class="space-y-3 text-gray-300">
                    <li><a href="#" class="hover:text-white transition-colors">Hakkımızda</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Ödeme Şekilleri</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">İptal ve İade Koşulları</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Gizlilik Sözleşmesi</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">KVKK Metni</a></li>
                    <li><a href="#contact" class="hover:text-white transition-colors">İletişim</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-6">İletişim</h3>
                <div class="space-y-3 text-gray-300">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-blue-400"></i>
                        <span>0850 533 3444</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-blue-400"></i>
                        <span>info@avantajbilisim.com</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-400"></i>
                        <span>Konya, Türkiye</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-blue-400"></i>
                        <span>09:00 - 18:00 (Hafta içi)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex flex-wrap justify-center md:justify-start space-x-6 mb-4 md:mb-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Mesafeli Satış Sözleşmesi</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Üyelik Sözleşmesi</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Elektronik Ticaret İleti Onayı</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Çerez Politikası</a>
                </div>
                <p class="text-gray-400 text-sm">© 2025 Avantaj Bilişim. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </div>
</footer>