@extends('layouts.app')

@section('title', 'Избранное — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-primary/10 blur-[100px] rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Личный кабинет' => route('dashboard.index'), 'Избранное' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 tracking-tight">Избранные <span class="text-primary-200">компании</span></h1>
            <p class="text-slate-400 font-medium text-lg max-w-2xl">Список автосалонов и магазинов, которые вы сохранили для быстрого доступа.</p>
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
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white p-10 md:p-16 rounded-[4rem] shadow-premium border border-slate-100">
                    @if($favorites->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($favorites as $dealer)
                                <div class="group relative bg-slate-50/50 rounded-[2.5rem] p-8 hover:bg-white hover:shadow-premium transition-all duration-500 border border-transparent hover:border-slate-100 flex flex-col justify-between">
                                    <div class="flex items-center gap-6 mb-8">
                                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0 p-2 group-hover:scale-105 transition-transform">
                                            <img src="{{ $dealer->logo_url }}" class="w-full h-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-heading font-black text-slate-900 text-lg line-clamp-1 group-hover:text-primary transition-colors tracking-tight">
                                                {{ $dealer->title }}
                                            </h3>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $dealer->city }}</span>
                                                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                                <div class="flex text-amber-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3 h-3 {{ $i <= round($dealer->reviews_avg_rating) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-auto">
                                        <a href="{{ route('dealerships.show', $dealer) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:text-primary transition-colors">
                                            Перейти
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                        <x-favorite-button :dealership="$dealer" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-16">
                            {{ $favorites->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="w-48 h-48 mx-auto mb-8 opacity-50">
                                <img src="{{ asset('images/placeholders/empty-favorites.svg') }}" alt="Empty" class="w-full h-full object-contain">
                            </div>
                            <h3 class="font-heading font-black text-slate-900 text-3xl mb-4 tracking-tight">Список избранного пуст</h3>
                            <p class="text-slate-400 mb-12 max-w-sm mx-auto font-medium">Сохраняйте интересные автосалоны и магазины, чтобы быстро возвращаться к ним позже.</p>
                            <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">Перейти в каталог</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
