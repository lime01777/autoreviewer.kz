@extends('layouts.app')

@section('title', 'Новости автомира — ' . config('site.site_name'))

@section('content')
    <!-- Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6">Автомобильный <span class="text-primary">пульс</span></h1>
            <p class="text-slate-400 max-w-xl mx-auto text-lg font-medium">Главные события, обзоры новинок и аналитика автомобильного рынка.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- News Grid -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    @forelse($news as $item)
                        <article class="group">
                            <a href="{{ route('news.show', $item) }}">
                                <div class="relative h-64 rounded-[2rem] overflow-hidden mb-6 shadow-xl shadow-slate-200/40">
                                    <img src="{{ $item->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="">
                                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-[10px] font-black uppercase tracking-widest">{{ $item->published_at->format('d.m.Y') }}</div>
                                </div>
                                <div class="space-y-4">
                                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">Авторынок</div>
                                    <h2 class="text-2xl font-heading font-black text-slate-900 group-hover:text-primary transition-colors leading-tight line-clamp-2">{{ $item->title }}</h2>
                                    <p class="text-slate-500 leading-relaxed text-sm line-clamp-3">{{ $item->excerpt }}</p>
                                    <div class="inline-flex items-center gap-2 text-sm font-black text-slate-900 border-b-2 border-primary/20 group-hover:border-primary transition-all pb-1">
                                        Читать далее
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] shadow-xl border border-slate-100">
                            <p class="text-slate-400 font-bold">Новостей пока нет.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $news->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-12">
                <!-- Search -->
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100">
                    <h3 class="text-xl font-heading font-black text-slate-900 mb-6">Поиск по новостям</h3>
                    <form action="{{ route('news.index') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Что ищем?" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
                    </form>
                </div>

                <!-- Banner -->
                <x-banner position="news_sidebar" />

                <!-- Popular Categories -->
                <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white overflow-hidden relative shadow-2xl shadow-primary/20">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[40px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <h3 class="text-xl font-heading font-black mb-8 relative z-10">Топ тем</h3>
                    <div class="flex flex-wrap gap-2 relative z-10">
                        @foreach(['Новинки', 'Электромобили', 'Рынок КЗ', 'Топ-10', 'Обзоры', 'Интервью'] as $tag)
                            <span class="px-4 py-2 bg-white/10 hover:bg-white/20 transition-colors rounded-xl text-xs font-bold cursor-pointer">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
