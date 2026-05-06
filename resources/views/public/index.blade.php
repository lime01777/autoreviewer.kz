@extends('layouts.app')

@section('title', config('site.site_name') . ' — Лучшие автосалоны и отзывы')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-slate-900 pt-32 pb-40 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover opacity-30" alt="">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/80 to-slate-900"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 text-primary-100 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-8 animate-fade-in">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                Более 500 проверенных автосалонов
            </div>
            <h1 class="text-5xl md:text-7xl font-heading font-black text-white leading-tight mb-8">
                Найдите свой идеальный <br> <span class="text-primary-600">автосалон</span> в пару кликов
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto mb-12">
                Честные отзывы, актуальные рейтинги и лучшие предложения от официальных дилеров и автоцентров по всей стране.
            </p>
            
            <!-- Hero Search -->
            <form action="{{ route('dealerships.index') }}" method="GET" class="max-w-3xl mx-auto flex flex-col md:flex-row gap-4 bg-white/5 p-2 rounded-3xl backdrop-blur-md border border-white/10 shadow-2xl">
                <div class="flex-grow flex items-center px-4">
                    <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="Название автосалона или город..." class="w-full bg-transparent border-none text-white focus:ring-0 placeholder:text-slate-500 py-4 font-medium">
                </div>
                <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-bold hover:bg-primary-600 transition shadow-lg shadow-primary/20">Найти</button>
            </form>
        </div>
    </section>

    <!-- Stats & Trust -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @foreach([
                ['count' => '500+', 'label' => 'Автосалонов'],
                ['count' => '12k', 'label' => 'Честных отзывов'],
                ['count' => '98%', 'label' => 'Доверие'],
                ['count' => '24/7', 'label' => 'Модерация'],
            ] as $stat)
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 text-center group hover:-translate-y-1 transition-all duration-300">
                    <div class="text-3xl font-heading font-black text-slate-900 mb-1 group-hover:text-primary transition-colors">{{ $stat['count'] }}</div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Dynamic Banner (Top) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <x-banner position="main_top" />
    </div>

    <!-- Featured Categories -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900">Популярные категории</h2>
                <p class="text-slate-500 mt-2">Ищите то, что нужно именно вам</p>
            </div>
            <a href="{{ route('dealerships.index') }}" class="text-primary font-bold hover:underline flex items-center gap-1 group">
                Все категории
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories->take(4) as $category)
                <a href="{{ route('dealerships.index') }}?category={{ $category->id }}" class="group relative h-64 rounded-3xl overflow-hidden shadow-lg border border-slate-100">
                    <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="text-white font-heading font-black text-xl mb-1">{{ $category->title }}</div>
                        <div class="text-white/60 text-xs font-bold uppercase tracking-widest">{{ $category->dealerships_count ?? 0 }} объектов</div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Top Rated Dealerships -->
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 mb-4">Рекомендуемые автосалоны</h2>
                <p class="text-slate-500 max-w-xl mx-auto">Организации с самым высоким рейтингом и положительными отзывами от наших пользователей.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredDealerships as $dealer)
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl transition duration-500 group">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $dealer->cover_image ?? 'https://images.unsplash.com/photo-1562141989-c5c79ac8f576?auto=format&fit=crop&q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $dealer->title }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            @if($dealer->is_featured)
                                <div class="absolute top-6 left-6 bg-accent text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">РЕКОМЕНДУЕМ</div>
                            @endif
                            <div class="absolute bottom-6 left-6 flex items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-2xl p-1.5 shadow-xl border border-white/20">
                                    <img src="{{ $dealer->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=100' }}" class="w-full h-full object-cover rounded-xl" alt="{{ $dealer->title }}">
                                </div>
                                <div>
                                    <h3 class="text-white font-heading font-black text-xl leading-tight">{{ $dealer->title }}</h3>
                                    <p class="text-white/70 text-xs font-medium">{{ $dealer->city }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="bg-primary/5 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-sm font-black text-primary">{{ $dealer->rating_avg }}</span>
                                    </div>
                                    <span class="text-slate-400 text-xs font-bold">{{ $dealer->reviews_count }} отзывов</span>
                                </div>
                                <div class="flex -space-x-2">
                                    @for($i=0; $i<3; $i++)
                                        <div class="w-6 h-6 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center overflow-hidden">
                                            <img src="https://i.pravatar.cc/100?u={{ $dealer->id + $i }}" class="w-full h-full object-cover" alt="User avatar">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            
                            <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2">{{ $dealer->short_description }}</p>
                            
                            <a href="{{ route('dealerships.show', $dealer) }}" class="flex items-center justify-center w-full bg-slate-50 hover:bg-primary hover:text-white py-4 rounded-2xl font-bold text-slate-900 transition-all group">
                                Подробнее
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-16">
                <a href="{{ route('dealerships.index') }}" class="inline-flex items-center gap-3 bg-white border border-slate-200 px-10 py-5 rounded-full font-black text-slate-900 hover:shadow-xl transition shadow-sm">
                    Смотреть весь каталог
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex justify-between items-end mb-16">
            <div>
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900">Новости авторынка</h2>
                <p class="text-slate-500 mt-4">Будьте в курсе последних событий</p>
            </div>
            <a href="{{ route('news.index') }}" class="hidden md:flex items-center gap-2 text-primary font-bold hover:underline">
                Архив новостей
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestNews as $news)
                <article class="group">
                    <a href="{{ route('news.show', $news) }}">
                        <div class="h-64 rounded-3xl overflow-hidden mb-6 shadow-lg shadow-slate-200/40">
                            <img src="{{ $news->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $news->title }}">
                        </div>
                        <div class="flex items-center gap-4 text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-4">
                            <span>{{ $news->published_at->format('d.m.Y') }}</span>
                            <span class="w-1 h-1 bg-primary rounded-full"></span>
                            <span class="text-primary">{{ $news->category ?? 'Авторынок' }}</span>
                        </div>
                        <h3 class="text-xl font-heading font-black text-slate-900 group-hover:text-primary transition-colors line-clamp-2 leading-tight">{{ $news->title }}</h3>
                        <p class="text-slate-500 text-sm mt-4 line-clamp-2 leading-relaxed">{{ $news->excerpt }}</p>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <!-- Call to Action -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        <div class="relative bg-primary-900 rounded-[3rem] p-8 md:p-20 overflow-hidden shadow-3xl shadow-primary/30">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/20 blur-[120px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                <div>
                    <h2 class="text-3xl md:text-5xl font-heading font-black text-white mb-6 leading-tight">Помогите другим <br> сделать правильный выбор</h2>
                    <p class="text-primary-100/70 text-lg mb-10 max-w-md">Ваш отзыв может стать решающим для будущего владельца авто. Поделитесь своим опытом покупки и обслуживания.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('dealerships.index') }}" class="bg-white text-primary-900 px-10 py-5 rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all">Написать отзыв</a>
                        <a href="{{ route('register') }}" class="bg-primary/20 text-white border border-white/10 px-10 py-5 rounded-2xl font-black backdrop-blur-md hover:bg-primary/30 transition-all">Присоединиться</a>
                    </div>
                </div>
                <div class="hidden lg:block relative">
                    <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=800" class="rounded-3xl shadow-2xl rotate-3 scale-110" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
