<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('site.site_name'))</title>
    <meta name="description" content="@yield('description', 'Честный каталог и отзывы об автомобильных магазинах и автосалонах.')">
    
    <!-- SEO & OpenGraph -->
    @yield('meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('site.site_name') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    <!-- Alpine.js (for mobile menu and interactions) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Fallback Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2563eb',
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        slate: {
                            900: '#0f172a',
                            950: '#020617',
                        },
                        accent: {
                            DEFAULT: '#f59e0b',
                            50: '#fffbeb',
                            600: '#d97706',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    boxShadow: {
                        'premium': '0 20px 50px -12px rgba(0, 0, 0, 0.05)',
                        'xl-premium': '0 30px 60px -15px rgba(37, 99, 235, 0.1)',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                        '5xl': '2.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .text-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes slow-zoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-slow-zoom {
            animation: slow-zoom 20s linear infinite alternate;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8fafc] text-slate-900 overflow-x-hidden selection:bg-primary selection:text-white" x-data="{ mobileMenuOpen: false }">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="glass border-b border-slate-200/60 sticky top-0 z-[100]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="group flex items-center gap-2">
                            <div class="bg-primary-900 text-white w-10 h-10 flex items-center justify-center rounded-xl font-black text-xl shadow-lg shadow-primary/20 group-hover:scale-105 transition-transform">
                                a
                            </div>
                            <span class="font-heading font-black text-2xl tracking-tight text-slate-900">auto<span class="text-primary">reviewer</span></span>
                        </a>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="/" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->is('/') ? 'text-primary bg-primary/5' : 'text-slate-600 hover:text-primary hover:bg-slate-50' }} transition-all">Главная</a>
                        <a href="{{ route('dealerships.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('dealerships.*') ? 'text-primary bg-primary/5' : 'text-slate-600 hover:text-primary hover:bg-slate-50' }} transition-all">Каталог</a>
                        <a href="{{ route('news.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('news.*') ? 'text-primary bg-primary/5' : 'text-slate-600 hover:text-primary hover:bg-slate-50' }} transition-all">Новости</a>
                        <a href="{{ route('about') ?? '#' }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:text-primary hover:bg-slate-50 transition-all">О проекте</a>
                        <a href="{{ route('contacts') ?? '#' }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:text-primary hover:bg-slate-50 transition-all">Контакты</a>
                    </div>

                    <!-- Actions -->
                    <div class="hidden lg:flex items-center space-x-4">
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 pl-2 pr-4 py-1.5 rounded-full border border-slate-200 hover:border-primary/30 transition-colors bg-white">
                                    <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden">
                                    <div class="px-4 py-2 border-b border-slate-50 mb-1">
                                        <p class="text-xs text-slate-400 uppercase font-black tracking-widest">Профиль</p>
                                    </div>
                                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Личный кабинет
                                    </a>
                                    <a href="{{ route('dashboard.favorites') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        Избранное
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-50 mt-1">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Выйти
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-primary transition-colors">Войти</a>
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-6 py-2.5 rounded-full font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5">
                                Регистрация
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Toggle -->
                    <div class="lg:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl bg-slate-50 text-slate-600">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-cloak x-transition.opacity class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[90]"></div>
            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="lg:hidden fixed right-0 top-0 bottom-0 w-[80%] max-w-sm bg-white z-[100] shadow-2xl p-6 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <span class="font-heading font-black text-xl tracking-tight">МЕНЮ</span>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-lg bg-slate-50 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <div class="flex flex-col space-y-2 flex-grow">
                    <a href="/" class="px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Главная</a>
                    <a href="{{ route('dealerships.index') }}" class="px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Каталог</a>
                    <a href="{{ route('news.index') }}" class="px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Новости</a>
                    <a href="#" class="px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">О проекте</a>
                    <a href="#" class="px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Контакты</a>
                </div>

                <div class="pt-6 border-t border-slate-100 space-y-4">
                    @auth
                        <div class="flex items-center gap-3 px-4">
                            <div class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-3 rounded-xl bg-slate-50 font-bold text-slate-700 text-center">Кабинет</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 rounded-xl text-red-500 font-bold">Выйти</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl bg-slate-50 font-bold text-slate-700 text-center">Войти</a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl bg-primary-600 text-white font-bold text-center shadow-lg shadow-primary/20">Регистрация</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white overflow-hidden relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
                <x-banner position="footer" />
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                    <!-- Brand -->
                    <div class="space-y-6">
                        <a href="/" class="flex items-center gap-2">
                            <div class="bg-primary-600 text-white w-8 h-8 flex items-center justify-center rounded-lg font-black text-lg">a</div>
                            <span class="font-heading font-black text-xl tracking-tight">auto<span class="text-primary">reviewer</span></span>
                        </a>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                            Профессиональная платформа для поиска и оценки автосалонов. Мы помогаем сделать автомобильный рынок прозрачным и честным.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary transition-colors"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary transition-colors"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        </div>
                    </div>

                    <!-- Links 1 -->
                    <div>
                        <h4 class="text-white font-bold mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            Каталог
                        </h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('dealerships.index') }}" class="text-slate-400 text-sm hover:text-white transition-colors">Все автосалоны</a></li>
                            <li><a href="{{ route('dealerships.index') }}?category=1" class="text-slate-400 text-sm hover:text-white transition-colors">Официальные дилеры</a></li>
                            <li><a href="{{ route('dealerships.index') }}?category=2" class="text-slate-400 text-sm hover:text-white transition-colors">Автоцентры</a></li>
                            <li><a href="{{ route('news.index') }}" class="text-slate-400 text-sm hover:text-white transition-colors">Новости рынка</a></li>
                        </ul>
                    </div>

                    <!-- Links 2 -->
                    <div>
                        <h4 class="text-white font-bold mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            Помощь
                        </h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-slate-400 text-sm hover:text-white transition-colors">Как оставить отзыв</a></li>
                            <li><a href="#" class="text-slate-400 text-sm hover:text-white transition-colors">Правила модерации</a></li>
                            <li><a href="#" class="text-slate-400 text-sm hover:text-white transition-colors">Вопросы и ответы</a></li>
                            <li><a href="#" class="text-slate-400 text-sm hover:text-white transition-colors">Рекламодателям</a></li>
                        </ul>
                    </div>

                    <!-- Links 3 -->
                    <div>
                        <h4 class="text-white font-bold mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            Контакты
                        </h4>
                        <div class="space-y-4 text-sm text-slate-400">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>{{ config('site.email') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ config('site.phone') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-20 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-xs font-medium">
                        &copy; {{ date('Y') }} {{ config('site.site_name') }}. Сделано с любовью к автомобилям.
                    </p>
                    <div class="flex gap-6">
                        <a href="#" class="text-slate-500 text-xs hover:text-white">Политика конфиденциальности</a>
                        <a href="#" class="text-slate-500 text-xs hover:text-white">Пользовательское соглашение</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
