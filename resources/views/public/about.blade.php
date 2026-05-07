@extends('layouts.app')

@section('title', 'О проекте — ' . config('site.site_name'))

@section('content')
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/60 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/10 blur-[120px] rounded-full"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <x-breadcrumb :items="['О проекте' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-7xl font-heading font-black text-white mb-8 leading-[1.1] tracking-tight">Мы делаем авторынок <span class="text-primary-200 underline decoration-primary/30 underline-offset-8">прозрачным</span></h1>
            <p class="text-slate-400 text-xl font-medium leading-relaxed">{{ config('site.site_name') }} — это не просто каталог, это сообщество честных мнений и проверенных компаний, созданное для помощи каждому автомобилисту.</p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 pb-32">
        <div class="bg-white rounded-[4rem] p-10 md:p-20 shadow-xl-premium border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-[80px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
            
            <div class="prose prose-xl prose-slate max-w-none text-slate-600 relative z-10">
                <h2 class="text-slate-900 font-heading font-black text-4xl mb-8 tracking-tight">Наша миссия</h2>
                <p class="text-lg leading-relaxed mb-12">Мы верим, что покупка и обслуживание автомобиля должны приносить радость, а не стресс. Наша цель — создать прозрачную среду, где каждый отзыв имеет значение, а лучшие компании получают заслуженное признание через доверие клиентов.</p>
                
                <h2 class="text-slate-900 font-heading font-black text-4xl mb-8 tracking-tight">Как это работает?</h2>
                <p class="text-lg leading-relaxed">Мы ежедневно анализируем рынок, собираем информацию о сотнях автосалонов и магазинов, модерируем каждый отзыв и строим честный рейтинг, основанный исключительно на реальном пользовательском опыте.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 my-20">
                    <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-100 group hover:bg-white hover:shadow-premium transition-all duration-500">
                        <div class="w-14 h-14 bg-primary text-white rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-primary/20 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r-1.158-1.96m0 0A9.956 9.956 0 0112 2.044a9.956 9.956 0 011.158 17.351m-1.158 1.96l1.158-1.96z"></path></svg></div>
                        <h4 class="font-black text-slate-900 text-xl mb-4">Бескомпромиссная честность</h4>
                        <p class="text-sm font-medium leading-relaxed text-slate-500">Каждый отзыв проходит многоуровневую модерацию и проверку на достоверность перед публикацией.</p>
                    </div>
                    <div class="bg-slate-50 p-10 rounded-[2.5rem] border border-slate-100 group hover:bg-white hover:shadow-premium transition-all duration-500">
                        <div class="w-14 h-14 bg-accent text-white rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-accent/20 group-hover:scale-110 transition-transform"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <h4 class="font-black text-slate-900 text-xl mb-4">Максимальная актуальность</h4>
                        <p class="text-sm font-medium leading-relaxed text-slate-500">Наша команда постоянно обновляет базу дилеров, контакты и следит за последними новостями автомира.</p>
                    </div>
                </div>

                <div class="bg-primary/5 rounded-[3rem] p-12 text-center border border-primary/10">
                    <p class="text-slate-900 font-bold text-xl mb-8 italic">"Присоединяйтесь к нашему сообществу, делитесь своим уникальным опытом и помогайте тысячам других автомобилистов делать правильный, осознанный выбор!"</p>
                    <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">Начать поиск</a>
                </div>
            </div>
        </div>
    </section>

@endsection
