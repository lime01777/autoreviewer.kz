@extends('layouts.app')

@section('title', 'Личный кабинет — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-primary/10 blur-[100px] rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Личный кабинет' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 tracking-tight">Личный <span class="text-primary-200">кабинет</span></h1>
            <p class="text-slate-400 font-medium text-lg max-w-2xl">Добро пожаловать, {{ $user->name }}. Управляйте своими отзывами и списком избранных компаний в одном месте.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            <!-- Sidebar Navigation -->
            <aside class="lg:col-span-1">
                <div class="sticky top-32">
                    @include('public.dashboard.partials.sidebar')
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-10">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['label' => 'Всего отзывов', 'value' => $user->reviews()->count(), 'color' => 'primary', 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
                        ['label' => 'В избранном', 'value' => $user->favorites()->count(), 'color' => 'accent', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                        ['label' => 'Статус', 'value' => $user->role === 'admin' ? 'Админ' : 'Клиент', 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r-1.158-1.96m0 0A9.956 9.956 0 0112 2.044a9.956 9.956 0 011.158 17.351m-1.158 1.96l1.158-1.96z'],
                    ] as $stat)
                        <div class="bg-white p-10 rounded-[3rem] shadow-premium border border-slate-100 group hover:-translate-y-1 transition-all duration-500">
                            <div class="flex justify-between items-start mb-8">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ $stat['label'] }}</p>
                                <div class="w-10 h-10 bg-slate-50 text-slate-300 rounded-xl flex items-center justify-center group-hover:bg-{{ $stat['color'] }}/10 group-hover:text-{{ $stat['color'] }} transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path></svg>
                                </div>
                            </div>
                            <p class="text-5xl font-heading font-black text-slate-900 tracking-tighter group-hover:scale-105 transition-transform origin-left">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Recent Activity Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Reviews Block -->
                    <div class="bg-white rounded-[3.5rem] shadow-premium border border-slate-100 overflow-hidden group">
                        <div class="px-12 py-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-heading font-black text-slate-900 text-2xl tracking-tight">Последние отзывы</h3>
                            <a href="{{ route('dashboard.reviews') }}" class="text-[10px] font-black uppercase tracking-widest text-primary border-b-2 border-primary/10 hover:border-primary transition-all pb-0.5">Все</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @forelse($reviews->take(4) as $review)
                                <div class="px-12 py-8 hover:bg-slate-50 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <p class="font-black text-slate-900 hover:text-primary transition-colors">{{ $review->dealership?->title ?? 'Удаленный автосалон' }}</p>
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $review->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $review->status === 'approved' ? 'Опубликован' : 'На модерации' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed italic">"{{ $review->text }}"</p>
                                    <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest mt-4">{{ $review->created_at->format('d.m.Y') }}</p>
                                </div>
                            @empty
                                <div class="px-12 py-20 text-center">
                                    <div class="w-48 h-48 mx-auto mb-6 opacity-50">
                                        <img src="{{ asset('images/placeholders/empty-reviews.svg') }}" alt="Empty" class="w-full h-full object-contain">
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Вы еще не оставляли отзывов</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Favorites Block -->
                    <div class="bg-white rounded-[3.5rem] shadow-premium border border-slate-100 overflow-hidden group">
                        <div class="px-12 py-10 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-heading font-black text-slate-900 text-2xl tracking-tight">Избранное</h3>
                            <a href="{{ route('dashboard.favorites') }}" class="text-[10px] font-black uppercase tracking-widest text-primary border-b-2 border-primary/10 hover:border-primary transition-all pb-0.5">Весь список</a>
                        </div>
                        <div class="p-10 space-y-6">
                            @forelse($favorites->take(4) as $dealer)
                                <a href="{{ route('dealerships.show', $dealer) }}" class="flex items-center gap-6 p-6 rounded-[2rem] bg-slate-50 hover:bg-white hover:shadow-premium border border-transparent hover:border-slate-100 transition-all duration-500 group/item">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0 p-2 group-hover:scale-110 transition-transform duration-500">
                                        <img src="{{ $dealer->logo_url }}" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-black text-slate-900 text-base line-clamp-1 group-hover/item:text-primary transition-colors">{{ $dealer->title }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $dealer->city }}</span>
                                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                            <span class="text-[9px] text-primary font-black uppercase tracking-widest">{{ number_format($dealer->reviews_avg_rating, 1) }} ★</span>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-300 group-hover/item:bg-primary group-hover/item:text-white transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                            @empty
                                <div class="py-20 text-center">
                                    <div class="w-48 h-48 mx-auto mb-6 opacity-50">
                                        <img src="{{ asset('images/placeholders/empty-favorites.svg') }}" alt="Empty" class="w-full h-full object-contain">
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Список избранного пуст</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
