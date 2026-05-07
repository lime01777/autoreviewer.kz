@extends('layouts.app')

@section('title', 'Новости автомира — ' . config('site.site_name'))

@section('content')
    <!-- Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden text-center md:text-left">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Новости' => route('news.index')]" class="mb-8 bg-white/5 border-white/10 text-white/60" />
            
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 leading-tight">
                Автомобильный <span class="text-primary-200">пульс</span>
            </h1>
            <p class="text-slate-400 max-w-xl text-lg font-medium">Главные события, обзоры новинок и аналитика автомобильного рынка.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            <!-- News Grid -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @forelse($news as $item)
                        <x-news-card :news="$item" />
                    @empty
                        <div class="col-span-full py-24 text-center bg-white rounded-[3rem] shadow-premium border border-slate-100">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-heading font-black text-slate-900 mb-3">Новостей пока нет</h3>
                            <p class="text-slate-400 max-w-sm mx-auto">Мы скоро добавим свежую информацию из мира автомобилей.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-20 pt-10 border-t border-slate-100">
                    {{ $news->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-12">
                <!-- Search -->
                <div class="bg-white p-10 rounded-[2.5rem] shadow-premium border border-slate-100">
                    <h3 class="text-xl font-heading font-black text-slate-900 mb-8">Поиск</h3>
                    <form action="{{ route('news.index') }}" method="GET" class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Что ищем?" class="w-full px-6 py-5 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-bold transition-all">
                        <button type="submit" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Banner -->
                <div class="rounded-[2.5rem] overflow-hidden shadow-premium">
                    <x-banner position="news_sidebar" />
                </div>

                <!-- Popular Categories -->
                <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white overflow-hidden relative shadow-xl-premium">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/30 blur-[60px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <h3 class="text-xl font-heading font-black mb-10 relative z-10">Топ тем</h3>
                    <div class="flex flex-wrap gap-3 relative z-10">
                        @foreach(['Новинки', 'Электромобили', 'Рынок КЗ', 'Топ-10', 'Обзоры', 'Интервью'] as $tag)
                            <span class="px-5 py-3 bg-white/10 hover:bg-primary transition-all rounded-xl text-xs font-black uppercase tracking-widest cursor-pointer border border-white/5">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
