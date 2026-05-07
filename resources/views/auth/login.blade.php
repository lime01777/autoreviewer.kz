@extends('layouts.app')

@section('title', 'Вход — ' . config('site.site_name'))

@section('content')
    <div class="min-h-screen flex items-center justify-center py-32 px-4 bg-white relative overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 blur-[120px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-accent/5 blur-[120px] translate-y-1/2 -translate-x-1/4 rounded-full"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-px h-px bg-primary/20 shadow-[0_0_400px_200px_rgba(37,99,235,0.1)]"></div>

        <div class="max-w-xl w-full relative z-10">
            <div class="bg-white rounded-[4rem] p-10 md:p-16 shadow-xl-premium border border-slate-100/60 backdrop-blur-sm bg-white/80">
                <div class="text-center mb-12">
                    <a href="/" class="inline-flex items-center gap-2 mb-10 group">
                        <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-xl group-hover:scale-110 transition-transform duration-500">
                            <span class="font-heading font-black text-2xl lowercase tracking-tighter">a</span>
                        </div>
                        <span class="text-2xl font-heading font-black text-slate-900 tracking-tighter">autoreviewer</span>
                    </a>
                    <h1 class="text-4xl font-heading font-black text-slate-900 mb-4 tracking-tight">С возвращением!</h1>
                    <p class="text-slate-400 font-medium text-lg leading-relaxed">Войдите в свой личный кабинет для управления отзывами</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-4">Ваш Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="email@autoreviewer.kz" class="w-full pl-14 pr-8 py-5 rounded-3xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-bold transition-all placeholder:text-slate-300">
                        </div>
                        @error('email') <p class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-4">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Пароль</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-slate-900 transition-colors">Забыли?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" required placeholder="••••••••" class="w-full pl-14 pr-8 py-5 rounded-3xl bg-slate-50 border-transparent focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 font-bold transition-all placeholder:text-slate-300">
                        </div>
                        @error('password') <p class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center px-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-slate-200 text-primary focus:ring-primary cursor-pointer">
                            <span class="ml-3 text-sm font-bold text-slate-500 select-none">Запомнить меня</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-3xl font-black text-xl hover:bg-primary transition-all shadow-xl-premium hover:-translate-y-1 active:scale-95 duration-300">
                        Войти в систему
                    </button>
                </form>

                <div class="mt-12 pt-10 border-t border-slate-50 text-center">
                    <p class="text-slate-400 font-medium">Впервые у нас? <a href="{{ route('register') }}" class="text-primary font-black hover:text-slate-900 transition-colors ml-1">Зарегистрироваться</a></p>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <a href="/" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Вернуться на главную
                </a>
            </div>
        </div>
    </div>

@endsection
