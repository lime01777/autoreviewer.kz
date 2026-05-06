@extends('layouts.app')

@section('title', 'Контакты — ' . config('site.site_name'))

@section('content')
    <section class="bg-slate-900 pt-32 pb-40 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-7xl font-heading font-black text-white mb-8 leading-tight">Свяжитесь с <span class="text-primary">нами</span></h1>
            <p class="text-slate-400 text-xl font-medium">Мы всегда открыты для предложений, обратной связи и сотрудничества.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Info Cards -->
            <div class="space-y-6">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100">
                    <div class="w-14 h-14 bg-primary/5 text-primary rounded-2xl flex items-center justify-center mb-8"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Напишите нам</h4>
                    <p class="text-xl font-black text-slate-900">{{ config('site.email') }}</p>
                </div>
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100">
                    <div class="w-14 h-14 bg-primary/5 text-primary rounded-2xl flex items-center justify-center mb-8"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Позвоните нам</h4>
                    <p class="text-xl font-black text-slate-900">{{ config('site.phone') }}</p>
                </div>
                <div class="bg-slate-900 p-10 rounded-[2.5rem] shadow-2xl shadow-primary/20 text-white">
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-primary mb-6">Социальные сети</h4>
                    <div class="flex gap-4">
                        @foreach(['tw', 'fb', 'ig', 'tg'] as $sm)
                            <a href="#" class="w-12 h-12 rounded-2xl bg-white/10 hover:bg-primary flex items-center justify-center transition-all"><span class="font-black text-xs uppercase">{{ $sm }}</span></a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl shadow-slate-200/60 border border-slate-100 h-full">
                    <h3 class="text-3xl font-heading font-black text-slate-900 mb-10">Форма обратной связи</h3>
                    <form action="#" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Имя</label>
                                <input type="text" placeholder="Иван Иванов" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email</label>
                                <input type="email" placeholder="ivan@mail.ru" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Сообщение</label>
                            <textarea rows="6" placeholder="Чем мы можем вам помочь?" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-medium"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl font-black text-lg hover:bg-primary-600 transition shadow-xl shadow-primary/20">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
