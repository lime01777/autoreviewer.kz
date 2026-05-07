@extends('layouts.app')

@section('title', 'Каталог автосалонов — ' . config('site.site_name'))

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-[radial-gradient(circle_at_50%_50%,#1e3a8a_0%,transparent_50%)] opacity-50"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Каталог' => route('dealerships.index')]" class="mb-8 bg-white/5 border-white/10 text-white/60" />
            
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 leading-tight">
                Каталог <span class="text-primary-200">автосалонов</span>
            </h1>
            <p class="text-slate-400 max-w-xl text-lg font-medium">Найдите проверенного дилера в вашем городе с помощью наших фильтров и отзывов реальных покупателей.</p>
        </div>
    </section>

    <!-- Filters & List -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <form action="{{ route('dealerships.index') }}" method="GET" id="filter-form" class="bg-white p-8 rounded-[2.5rem] shadow-premium border border-slate-100 sticky top-24">
                    <div class="space-y-8">
                        <!-- Search -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Поиск</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название..." class="w-full pl-5 pr-12 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 text-sm font-semibold transition-all">
                                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Тип объекта</label>
                            <select name="type" onchange="this.form.submit()" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 text-sm font-semibold cursor-pointer appearance-none transition-all">
                                <option value="">Все типы</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Категория</label>
                            <select name="category" onchange="this.form.submit()" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 text-sm font-semibold cursor-pointer appearance-none transition-all">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Бренд</label>
                            <select name="brand" onchange="this.form.submit()" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 text-sm font-semibold cursor-pointer appearance-none transition-all">
                                <option value="">Все бренды</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Город</label>
                            <select name="city" onchange="this.form.submit()" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 text-sm font-semibold cursor-pointer appearance-none transition-all">
                                <option value="">Все города</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Сортировка</label>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach([
                                    'rating' => 'По рейтингу',
                                    'reviews' => 'По отзывам',
                                    'newest' => 'Сначала новые',
                                    'recommended' => 'Рекомендуемые',
                                    'alphabetical' => 'По алфавиту',
                                ] as $key => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="sort" value="{{ $key }}" onchange="this.form.submit()" {{ request('sort', 'newest') == $key ? 'checked' : '' }} class="peer appearance-none w-5 h-5 rounded-full border-2 border-slate-200 checked:border-primary transition-all">
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity">
                                                <div class="w-2 h-2 bg-primary rounded-full"></div>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-600 group-hover:text-primary transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <a href="{{ route('dealerships.index') }}" class="flex items-center justify-center gap-2 w-full py-4 rounded-2xl bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all group">
                                <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Сбросить всё
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Banner (Top of List) -->
                <div class="rounded-[2.5rem] overflow-hidden shadow-premium">
                    <x-banner position="catalog_top" />
                </div>

                <!-- Results Info -->
                <div class="flex items-center justify-between px-8 py-5 bg-white rounded-[1.8rem] shadow-sm border border-slate-100">
                    <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        Найдено объектов: <span class="text-slate-900 font-black text-sm ml-2">{{ $dealerships->total() }}</span>
                    </div>
                    <div class="flex gap-3">
                        @if(request()->anyFilled(['search', 'category', 'brand', 'type', 'city']))
                            <div class="hidden md:flex gap-2">
                                @foreach(request()->only(['search', 'category', 'brand', 'type', 'city']) as $key => $value)
                                    @if($value)
                                        <span class="px-3 py-1 bg-slate-50 text-[10px] font-bold text-slate-400 rounded-lg flex items-center gap-2">
                                            {{ $key }}: {{ $value }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
                    @forelse($dealerships as $dealer)
                        <x-dealership-card :dealership="$dealer" />
                    @empty
                        <div class="col-span-full py-32 text-center bg-white rounded-[4rem] shadow-premium border border-slate-100">
                            <div class="w-48 h-48 mx-auto mb-10 opacity-50">
                                <img src="{{ asset('images/placeholders/empty-catalog.svg') }}" alt="Empty" class="w-full h-full object-contain">
                            </div>
                            <h3 class="text-3xl font-heading font-black text-slate-900 mb-4 tracking-tight">Ничего не найдено</h3>
                            <p class="text-slate-400 max-w-sm mx-auto mb-12 font-medium">Попробуйте изменить параметры поиска или сбросить фильтры, чтобы увидеть больше результатов.</p>
                            <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-12 py-6 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">Показать все</a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-20 pt-12 border-t border-slate-100">
                    {{ $dealerships->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
