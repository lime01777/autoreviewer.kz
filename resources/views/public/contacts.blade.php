@extends('layouts.app')

@section('title', 'Контакты — ' . config('site.site_name'))

@section('content')
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/60 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 right-1/4 w-[600px] h-[600px] bg-primary/10 blur-[100px] rounded-full"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <x-breadcrumb :items="['Контакты' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-7xl font-heading font-black text-white mb-8 leading-[1.1] tracking-tight">Свяжитесь с <span class="text-primary-200">нами</span></h1>
            <p class="text-slate-400 text-xl font-medium leading-relaxed">Мы всегда открыты для предложений, обратной связи и предложений по сотрудничеству.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Info Cards -->
            <div class="space-y-6">
                <div class="bg-white p-10 rounded-[3rem] shadow-premium border border-slate-100 group hover:-translate-y-1 transition-all duration-500">
                    <div class="w-16 h-16 bg-primary/5 text-primary rounded-2xl flex items-center justify-center mb-10 group-hover:bg-primary group-hover:text-white transition-colors duration-500 shadow-xl shadow-transparent group-hover:shadow-primary/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Напишите нам</h4>
                    <a href="mailto:{{ config('site.email') }}" class="text-xl font-black text-slate-900 hover:text-primary transition-colors">{{ config('site.email') }}</a>
                </div>
                <div class="bg-white p-10 rounded-[3rem] shadow-premium border border-slate-100 group hover:-translate-y-1 transition-all duration-500">
                    <div class="w-16 h-16 bg-accent/5 text-accent rounded-2xl flex items-center justify-center mb-10 group-hover:bg-accent group-hover:text-white transition-colors duration-500 shadow-xl shadow-transparent group-hover:shadow-accent/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Позвоните нам</h4>
                    <a href="tel:{{ config('site.phone') }}" class="text-xl font-black text-slate-900 hover:text-primary transition-colors">{{ config('site.phone') }}</a>
                </div>
                <div class="bg-slate-900 p-10 rounded-[3rem] shadow-xl-premium text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[40px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-8 relative z-10">Мы в соцсетях</h4>
                    <div class="flex gap-4 relative z-10">
                        @foreach(['tw', 'fb', 'ig', 'tg'] as $sm)
                            <a href="#" class="w-14 h-14 rounded-2xl bg-white/5 border border-white/5 hover:bg-primary hover:border-primary flex items-center justify-center transition-all duration-300 group/sm shadow-lg">
                                <span class="font-black text-xs uppercase group-hover:scale-110 transition-transform">{{ $sm }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-10 md:p-20 rounded-[4rem] shadow-premium border border-slate-100 h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-accent"></div>
                    <h3 class="text-3xl md:text-4xl font-heading font-black text-slate-900 mb-4 tracking-tight">Напишите нам</h3>
                    <p class="text-slate-400 mb-12 font-medium">Оставьте сообщение, и наш менеджер свяжется с вами в ближайшее время.</p>
                    
                    <form action="#" class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Ваше имя</label>
                                <input type="text" placeholder="Иван Иванов" class="w-full px-8 py-5 rounded-[1.5rem] bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-bold transition-all">
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Ваш Email</label>
                                <input type="email" placeholder="ivan@autoreviewer.kz" class="w-full px-8 py-5 rounded-[1.5rem] bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-bold transition-all">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Ваше сообщение</label>
                            <textarea rows="6" placeholder="Чем мы можем вам помочь?" class="w-full px-8 py-6 rounded-[2rem] bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-medium transition-all"></textarea>
                        </div>
                        <div class="flex flex-col md:flex-row items-center justify-between gap-8 pt-4">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest max-w-[200px]">Ответ в течение 24 часов</p>
                            <button type="submit" class="w-full md:w-auto bg-slate-900 text-white px-16 py-6 rounded-[1.5rem] font-black text-xl hover:bg-primary transition-all shadow-xl-premium hover:-translate-y-1">Отправить письмо</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
