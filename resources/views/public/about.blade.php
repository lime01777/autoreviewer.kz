@extends('layouts.app')

@section('title', 'О проекте — ' . config('site.site_name'))

@section('content')
    <section class="bg-slate-900 pt-32 pb-40 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-7xl font-heading font-black text-white mb-8 leading-tight">Мы делаем авторынок <span class="text-primary">прозрачным</span></h1>
            <p class="text-slate-400 text-xl font-medium">{{ config('site.site_name') }} — это не просто каталог, это сообщество честных мнений и проверенных компаний.</p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-24">
        <div class="bg-white rounded-[3rem] p-10 md:p-20 shadow-2xl shadow-slate-200/60 border border-slate-100">
            <div class="prose prose-xl prose-slate max-w-none text-slate-600">
                <h2 class="text-slate-900 font-heading font-black">Наша миссия</h2>
                <p>Мы верим, что покупка и обслуживание автомобиля должны приносить радость, а не стресс. Наша цель — создать среду, где каждый отзыв имеет значение, а лучшие компании получают заслуженное признание.</p>
                
                <h2 class="text-slate-900 font-heading font-black">Как это работает?</h2>
                <p>Мы собираем информацию о сотнях автосалонов и магазинов, модерируем каждый отзыв и строим честный рейтинг, основанный на реальном опыте пользователей.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-16">
                    <div class="bg-slate-50 p-8 rounded-3xl">
                        <div class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary/20"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r-1.158-1.96m0 0A9.956 9.956 0 0112 2.044a9.956 9.956 0 011.158 17.351m-1.158 1.96l1.158-1.96z"></path></svg></div>
                        <h4 class="font-black text-slate-900 mb-2">Честность</h4>
                        <p class="text-sm">Каждый отзыв проходит строгую модерацию перед публикацией.</p>
                    </div>
                    <div class="bg-slate-50 p-8 rounded-3xl">
                        <div class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary/20"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <h4 class="font-black text-slate-900 mb-2">Актуальность</h4>
                        <p class="text-sm">Мы ежедневно обновляем информацию о дилерах и новостях рынка.</p>
                    </div>
                </div>

                <p>Присоединяйтесь к нам, делитесь своим опытом и помогайте другим автомобилистам делать правильный выбор!</p>
            </div>
        </div>
    </section>
@endsection
