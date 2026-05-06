@extends('layouts.app')

@section('title', 'Вход — ' . config('site.site_name'))

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center py-24 px-4 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/5 blur-[120px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-full bg-primary/5 blur-[120px] translate-y-1/2 -translate-x-1/4 rounded-full"></div>

        <div class="max-w-md w-full bg-white rounded-[3rem] p-10 md:p-12 shadow-2xl shadow-slate-200/60 border border-slate-100 relative z-10">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-900 text-white rounded-2xl font-black text-2xl shadow-xl shadow-primary/20 mb-6">a</div>
                <h1 class="text-3xl font-heading font-black text-slate-900 mb-2">С возвращением!</h1>
                <p class="text-slate-400 font-medium">Войдите в свой аккаунт {{ config('site.site_name') }}</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400">Пароль</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline">Забыли?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-slate-200 text-primary focus:ring-primary">
                    <label for="remember_me" class="ml-3 text-sm font-bold text-slate-600">Запомнить меня</label>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl font-black text-lg hover:bg-primary-600 transition shadow-xl shadow-primary/20 transform active:scale-95">
                    Войти
                </button>
            </form>

            <div class="mt-10 pt-10 border-t border-slate-50 text-center">
                <p class="text-slate-400 font-medium">Нет аккаунта? <a href="{{ route('register') }}" class="text-primary font-black hover:underline">Регистрация</a></p>
            </div>
        </div>
    </div>
@endsection
