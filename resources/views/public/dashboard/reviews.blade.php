@extends('layouts.app')

@section('title', 'Мои отзывы — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-primary/10 blur-[100px] rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Личный кабинет' => route('dashboard.index'), 'Мои отзывы' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 tracking-tight">Мои <span class="text-primary-200">отзывы</span></h1>
            <p class="text-slate-400 font-medium text-lg max-w-2xl">История ваших публикаций и их текущий статус модерации.</p>
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
                    @if($reviews->count() > 0)
                        <div class="space-y-8">
                            @foreach($reviews as $review)
                                @php $color = $review->status_color; @endphp
                                <div class="p-8 rounded-[2.5rem] bg-slate-50/50 border border-slate-100 hover:bg-white hover:shadow-premium transition-all duration-500 group">

                                    {{-- Header: dealership + status --}}
                                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
                                        <div class="flex items-center gap-5">
                                            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0 p-1.5 group-hover:scale-105 transition-transform">
                                                @if($review->dealership)
                                                    <img src="{{ $review->dealership->logo_url }}" class="w-full h-full object-contain" alt="{{ $review->dealership->title }}" loading="lazy">
                                                @else
                                                    <img src="{{ asset('images/placeholders/logo.svg') }}" class="w-full h-full object-contain" alt="Удалённый салон">
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-heading font-black text-slate-900 text-lg tracking-tight leading-tight">
                                                    {{ $review->dealership?->title ?? 'Удалённый автосалон' }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                                        {{ $review->created_at->format('d.m.Y') }}
                                                    </p>
                                                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                                    <div class="flex items-center gap-1 text-amber-400">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                        @endfor
                                                        <span class="text-slate-500 font-black text-xs ml-1">{{ $review->rating }}/5</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="flex-shrink-0 px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm
                                            {{ $review->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                                            {{ $review->status === 'pending'  ? 'bg-amber-50 text-amber-600 border border-amber-100'   : '' }}
                                            {{ $review->status === 'rejected' ? 'bg-rose-50 text-rose-600 border border-rose-100'       : '' }}
                                        ">
                                            {{ $review->status_label }}
                                        </span>
                                    </div>

                                    {{-- Review text --}}
                                    <blockquote class="text-slate-700 text-base leading-relaxed font-medium italic border-l-4 border-primary/10 pl-4 mb-6">
                                        "{{ $review->text }}"
                                    </blockquote>

                                    {{-- Pros / Cons --}}
                                    @if($review->pros || $review->cons)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                            @if($review->pros)
                                                <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100/50">
                                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        Плюсы
                                                    </div>
                                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $review->pros }}</p>
                                                </div>
                                            @endif
                                            @if($review->cons)
                                                <div class="bg-rose-50/50 p-5 rounded-2xl border border-rose-100/50">
                                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-rose-600 mb-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Минусы
                                                    </div>
                                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $review->cons }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Admin comment for rejected --}}
                                    @if($review->status === 'rejected' && $review->admin_comment)
                                        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 mb-6">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-1.5">Причина отклонения:</p>
                                            <p class="text-sm text-rose-700">{{ $review->admin_comment }}</p>
                                        </div>
                                    @endif

                                    @if($review->dealership)
                                        <div class="pt-5 border-t border-slate-50 flex justify-end">
                                            <a href="{{ route('dealerships.show', $review->dealership) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-primary hover:text-slate-900 transition-colors">
                                                Перейти к автосалону
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-16">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <img src="{{ asset('images/placeholders/empty-reviews.svg') }}" alt="Нет отзывов" class="w-48 h-48 mx-auto mb-8 opacity-60">
                            <h3 class="font-heading font-black text-slate-900 text-3xl mb-4 tracking-tight">Вы пока не оставляли отзывы</h3>
                            <p class="text-slate-400 mb-12 max-w-sm mx-auto font-medium">Ваше мнение важно для других водителей! Поделитесь своим опытом посещения автосалонов.</p>
                            <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">Найти автосалон</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
