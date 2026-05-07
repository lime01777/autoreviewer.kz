@extends('layouts.app')

@section('title', 'autoreviewer — Найдите идеальный автосалон в Казахстане')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 min-h-[85vh] flex flex-col justify-center pt-20">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=2000"
                alt="Hero background" class="h-full w-full object-cover opacity-30 scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/40 to-slate-950"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/20 via-transparent to-slate-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(37,99,235,0.15),transparent_70%)]"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 pb-32 sm:px-6 md:pt-2 lg:px-8 lg:pt-6">
            <div class="mx-auto max-w-5xl text-center">
                <div
                    class="mb-10 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[10px] font-black uppercase tracking-[0.25em] text-white/80 backdrop-blur-md">
                    <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    Ваш гид по авторынку Казахстана
                </div>

                <h1 class="text-4xl font-black leading-[1.1] tracking-tight text-white sm:text-7xl md:text-8xl">
                    Найдите лучший<br>
                    <span class="text-white">автосалон сегодня</span>
                </h1>

                <p class="mx-auto mt-8 max-w-2xl text-base font-medium leading-relaxed text-slate-400 md:text-xl">
                    Честные отзывы, актуальные цены и проверенные дилеры в одном месте. Мы помогаем сделать правильный
                    выбор.
                </p>

                <div class="mx-auto mt-12 w-full max-w-3xl">
                    <form action="{{ route('dealerships.index') }}" method="GET"
                        class="group flex w-full flex-col gap-3 rounded-[2.5rem] border border-white/10 bg-white/5 p-2.5 backdrop-blur-3xl transition-all focus-within:border-primary/50 focus-within:bg-white/10 sm:flex-row shadow-2xl">
                        <div class="flex flex-1 items-center gap-4 px-5">
                            <svg class="h-6 w-6 text-slate-400 group-focus-within:text-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" placeholder="Поиск по названию, городу или услуге..."
                                class="w-full border-none bg-transparent py-4 text-base font-bold text-white placeholder:text-slate-500 focus:ring-0">
                        </div>
                        <button type="submit"
                            class="rounded-[1.5rem] bg-primary px-10 py-4 text-sm font-black uppercase tracking-widest text-white transition hover:bg-primary-700 shadow-xl shadow-primary/20 active:scale-95">
                            Найти дилера
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="relative z-20 mx-auto -mt-20 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @php
                $heroCategories = [
                    ['name' => 'Новые авто', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => route('dealerships.index', ['type' => 'official_dealer'])],
                    ['name' => 'С пробегом', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => route('dealerships.index', ['type' => 'used'])],
                    ['name' => 'Дилеры', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'link' => route('dealerships.index', ['type' => 'dealership'])],
                    ['name' => 'Магазины', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'link' => route('dealerships.index', ['type' => 'shop'])],
                    ['name' => 'Сервис', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'link' => route('dealerships.index', ['type' => 'service'])],
                    ['name' => 'Запчасти', 'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z', 'link' => route('dealerships.index', ['type' => 'parts'])],
                ];
            @endphp

            @foreach($heroCategories as $cat)
                <a href="{{ $cat['link'] }}"
                    class="group rounded-[2.5rem] bg-white p-6 text-center shadow-premium transition-all hover:-translate-y-2 hover:shadow-2xl active:scale-95 border border-slate-50">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/5 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 transition-colors">
                        {{ $cat['name'] }}
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-banner position="main_top" />
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-5xl">Популярные автосалоны</h2>
                <p class="mt-3 max-w-2xl text-sm font-medium text-slate-500 sm:text-base">
                    Подборка компаний с самым высоким количеством одобренных отзывов и стабильным рейтингом.
                </p>
            </div>
            <a href="{{ route('dealerships.index', ['sort' => 'reviews']) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-5 py-3 text-xs font-black uppercase tracking-wider text-slate-700 transition hover:bg-slate-900 hover:text-white">
                Смотреть все
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($popularDealerships as $dealer)
                <x-dealership-card :dealership="$dealer" />
            @empty
                <div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 py-14 text-center">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400">Нет данных для отображения</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-5xl">Рекомендуемые партнеры</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm font-medium text-slate-500 sm:text-base">
                    Компании с подтвержденными данными и высоким уровнем клиентского сервиса.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredDealerships as $dealer)
                    <x-dealership-card :dealership="$dealer" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div class="mb-14">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Свежие отзывы</h2>
                        <a href="{{ route('dealerships.index') }}"
                            class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-slate-900">Все
                            отзывы</a>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @forelse($latestReviews as $review)
                            <x-review-card :review="$review" />
                        @empty
                            <div class="col-span-full rounded-3xl border border-slate-100 bg-white p-8 text-center">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-300">Пока нет отзывов</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div>
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Последние новости</h2>
                        <a href="{{ route('news.index') }}"
                            class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-slate-900">Читать
                            больше</a>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($latestNews as $news)
                            <x-news-card :news="$news" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="space-y-8 lg:sticky lg:top-24">
                    <x-banner position="main_sidebar" />
                    <div class="rounded-3xl bg-slate-900 p-8 text-white">
                        <h3 class="text-2xl font-black leading-tight">Ваш бизнес на autoreviewer</h3>
                        <p class="mt-4 text-sm font-medium leading-relaxed text-slate-300">
                            Разместите автосалон, собирайте отзывы и увеличивайте доверие клиентов.
                        </p>
                        <a href="{{ route('contacts') }}"
                            class="mt-6 inline-flex rounded-xl bg-primary px-6 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-white hover:text-slate-900">
                            Разместить компанию
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto mb-24 max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-banner position="main_bottom" />
    </section>
@endsection