<div class="bg-gray-800 text-white text-sm py-2">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <span><i class="fas fa-phone mr-2"></i>0850 533 3444</span>
            <span><i class="fas fa-envelope mr-2"></i>info@avantajbilisim.com</span>
        </div>
        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}" class="hover:text-blue-300 transition-colors">Giriş Yap</a>
                <a href="{{ route('register') }}" class="hover:text-blue-300 transition-colors">Üye Ol</a>
            @else
                <div class="relative group">
                    <button class="flex items-center space-x-2 hover:text-blue-300 transition-colors">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Çıkış Yap</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    * { font-family: 'Inter', sans-serif; }
</style>