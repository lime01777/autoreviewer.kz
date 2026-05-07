@extends('layouts.app')

@section('title', '404 — Страница не найдена')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-32 px-4 bg-white relative overflow-hidden text-center">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 blur-[120px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-accent/5 blur-[120px] translate-y-1/2 -translate-x-1/4 rounded-full"></div>

        <div class="max-w-2xl w-full relative z-10">
            <h1 class="text-[12rem] md:text-[18rem] font-heading font-black text-slate-900/5 leading-none tracking-tighter absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 select-none">404</h1>
            
            <div class="relative">
                <div class="w-32 h-32 bg-primary/10 rounded-4xl flex items-center justify-center mx-auto mb-12 shadow-xl shadow-primary/5">
                    <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-4xl md:text-6xl font-heading font-black text-slate-900 mb-6 tracking-tight">Упс! Мы заехали <span class="text-primary-200">не туда</span></h2>
                <p class="text-slate-400 font-medium text-lg mb-12 max-w-md mx-auto leading-relaxed">Похоже, страница, которую вы ищете, была перемещена или никогда не существовала.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="/" class="bg-slate-900 text-white px-12 py-5 rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-xl-premium hover:-translate-y-1">Вернуться на главную</a>
                    <a href="{{ route('dealerships.index') }}" class="bg-slate-50 text-slate-600 px-12 py-5 rounded-2xl font-black text-sm hover:bg-white border border-slate-100 hover:shadow-premium transition-all hover:-translate-y-1">В каталог дилеров</a>
                </div>
            </div>
        </div>
    </div>
@endsection
