@extends('layouts.app')

@section('title', 'Личный кабинет — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-4xl md:text-5xl font-heading font-black text-white mb-4">Мой <span class="text-primary">профиль</span></h1>
            <p class="text-slate-400 font-medium">Добро пожаловать, {{ $user->name }}. Здесь ваша активность на AVTOREWIER.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="lg:col-span-1">
                @include('public.dashboard.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['label' => 'Всего отзывов', 'value' => $user->reviews()->count(), 'color' => 'primary'],
                        ['label' => 'В избранном', 'value' => $user->favorites()->count(), 'color' => 'accent'],
                        ['label' => 'Статус профиля', 'value' => $user->role === 'admin' ? 'Админ' : 'Базовый', 'color' => 'emerald'],
                    ] as $stat)
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 group hover:-translate-y-1 transition-all">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ $stat['label'] }}</p>
                            <p class="text-4xl font-heading font-black text-slate-900 group-hover:text-{{ $stat['color'] }} transition-colors">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Recent Activity Content (Review/Favorite Snippets) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Reviews Block -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="font-heading font-black text-slate-900 text-xl">Последние отзывы</h3>
                            <a href="{{ route('dashboard.reviews') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline">Все</a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @forelse($reviews->take(3) as $review)
                                <div class="px-10 py-6 hover:bg-slate-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-bold text-slate-900 line-clamp-1">{{ $review->dealership?->title ?? 'Удаленный автосалон' }}</p>
                                        <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $review->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $review->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400 line-clamp-2 italic">"{{ $review->text }}"</p>
                                </div>
                            @empty
                                <div class="px-10 py-12 text-center text-slate-400 font-medium text-sm">Вы еще не оставляли отзывов.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Favorites Block -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                        <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="font-heading font-black text-slate-900 text-xl">Избранное</h3>
                            <a href="{{ route('dashboard.favorites') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline">Весь список</a>
                        </div>
                        <div class="p-8 space-y-4">
                            @forelse($favorites->take(3) as $dealer)
                                <a href="{{ route('dealerships.show', $dealer) }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 transition group">
                                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $dealer->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=100' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-primary transition-colors">{{ $dealer->title }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $dealer->city }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="py-12 text-center text-slate-400 font-medium text-sm">Список пуст.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
