@extends('layouts.app')

@section('title', 'Каталог автосалонов — ' . config('site.site_name'))

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <nav class="flex text-xs font-black uppercase tracking-widest text-slate-500 mb-6 gap-2 items-center">
                <a href="/" class="hover:text-white transition-colors">Главная</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-white">Каталог</span>
            </nav>
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 leading-tight">
                Каталог <span class="text-primary">автосалонов</span>
            </h1>
            <p class="text-slate-400 max-w-xl text-lg">Найдите проверенного дилера в вашем городе с помощью наших фильтров и отзывов реальных покупателей.</p>
        </div>
    </section>

    <!-- Filters & List -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <form action="{{ route('dealerships.index') }}" method="GET" class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24">
                    <div class="space-y-8">
                        <!-- Search -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Поиск</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название..." class="w-full pl-4 pr-10 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-primary text-sm font-semibold">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Категория</label>
                            <select name="category" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-primary text-sm font-semibold cursor-pointer">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Город</label>
                            <select name="city" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-primary text-sm font-semibold cursor-pointer">
                                <option value="">Все города</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Сортировка</label>
                            <div class="space-y-2">
                                @foreach([
                                    'rating' => 'По рейтингу',
                                    'reviews' => 'По отзывам',
                                    'new' => 'Сначала новые',
                                    'featured' => 'Рекомендуемые',
                                ] as $key => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="sort" value="{{ $key }}" onchange="this.form.submit()" {{ request('sort', 'featured') == $key ? 'checked' : '' }} class="w-4 h-4 text-primary focus:ring-primary border-slate-200">
                                        <span class="text-sm font-medium text-slate-600 group-hover:text-primary transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-sm hover:bg-slate-800 transition shadow-lg">Применить</button>
                        <a href="{{ route('dealerships.index') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-primary">Сбросить все</a>
                    </div>
                </form>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Banner (Top of List) -->
                <x-banner position="catalog_top" />

                <!-- Results Info -->
                <div class="flex items-center justify-between px-4">
                    <div class="text-slate-400 text-sm">Найдено: <span class="text-slate-900 font-bold">{{ $dealerships->total() }}</span></div>
                    <div class="hidden md:flex gap-2">
                        <button class="p-2 rounded-lg bg-primary text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg></button>
                        <button class="p-2 rounded-lg bg-white text-slate-400 hover:bg-slate-50 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($dealerships as $dealer)
                        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl transition duration-500 group">
                            <div class="relative h-56 overflow-hidden">
                                <img src="{{ $dealer->cover_image ?? 'https://images.unsplash.com/photo-1562141989-c5c79ac8f576?auto=format&fit=crop&q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $dealer->title }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                @if($dealer->is_featured)
                                    <div class="absolute top-6 left-6 bg-accent text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">РЕКОМЕНДУЕМ</div>
                                @endif
                                <div class="absolute bottom-6 left-6 right-6 flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white rounded-xl p-1 shadow-xl border border-white/20 flex-shrink-0">
                                        <img src="{{ $dealer->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=100' }}" class="w-full h-full object-cover rounded-lg" alt="{{ $dealer->title }}">
                                    </div>
                                    <h3 class="text-white font-heading font-black text-lg leading-tight line-clamp-1">{{ $dealer->title }}</h3>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center gap-1.5 bg-primary/5 px-3 py-1.5 rounded-xl">
                                        <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-sm font-black text-primary">{{ $dealer->rating_avg }}</span>
                                    </div>
                                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">{{ $dealer->reviews_count }} отзывов</span>
                                </div>
                                
                                <div class="space-y-3 mb-8">
                                    <div class="flex items-start gap-3 text-sm text-slate-500">
                                        <svg class="w-4 h-4 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="line-clamp-1">{{ $dealer->address }}, {{ $dealer->city }}</span>
                                    </div>
                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-2 italic">"{{ $dealer->short_description }}"</p>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('dealerships.show', $dealer) }}" class="flex-grow flex items-center justify-center bg-slate-900 text-white py-4 rounded-2xl font-bold text-sm hover:bg-primary transition-all duration-300">Подробнее</a>
                                    <x-favorite-button :dealership="$dealer" />
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-heading font-black text-slate-900 mb-2">Ничего не найдено</h3>
                            <p class="text-slate-400 max-w-xs mx-auto">Попробуйте изменить параметры поиска или фильтры.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12 px-4">
                    {{ $dealerships->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
