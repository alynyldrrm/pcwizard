<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Avantaj Bilişim')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        .gradient-blue { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .gradient-red { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
        
        .category-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(37, 99, 235, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .category-card:hover::before { opacity: 1; }
        
        .hero-swiper .swiper-slide {
            background-size: cover;
            background-position: center;
            min-height: 500px;
        }
        .swiper-pagination-bullet {
            background: #2563eb;
            opacity: 0.3;
        }
        .swiper-pagination-bullet-active { opacity: 1; }
        
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
    
    @stack('styles')
</head>
<body class="bg-gray-50">

    @include('partials.frontend.topbar')
    @include('partials.frontend.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.frontend.footer')
    @include('partials.frontend.modals')
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>


    @stack('scripts')
</body>
</html>