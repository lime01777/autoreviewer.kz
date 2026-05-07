@extends('layouts.app')

@section('title', '500 — Ошибка сервера')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-32 px-4 bg-white relative overflow-hidden text-center">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-rose-500/5 blur-[120px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-amber-500/5 blur-[120px] translate-y-1/2 -translate-x-1/4 rounded-full"></div>

        <div class="max-w-2xl w-full relative z-10">
            <h1 class="text-[12rem] md:text-[18rem] font-heading font-black text-slate-900/5 leading-none tracking-tighter absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 select-none">500</h1>
            
            <div class="relative">
                <div class="w-32 h-32 bg-rose-50 rounded-4xl flex items-center justify-center mx-auto mb-12 shadow-xl shadow-rose-500/5">
                    <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h2 class="text-4xl md:text-6xl font-heading font-black text-slate-900 mb-6 tracking-tight">Внезапная <span class="text-rose-500">поломка</span></h2>
                <p class="text-slate-400 font-medium text-lg mb-12 max-w-md mx-auto leading-relaxed">Наши механики уже работают над устранением технической неисправности. Пожалуйста, попробуйте позже.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <button onclick="window.location.reload()" class="bg-slate-900 text-white px-12 py-5 rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-xl-premium hover:-translate-y-1">Перезагрузить страницу</button>
                    <a href="/" class="bg-slate-50 text-slate-600 px-12 py-5 rounded-2xl font-black text-sm hover:bg-white border border-slate-100 hover:shadow-premium transition-all hover:-translate-y-1">Вернуться на главную</a>
                </div>
            </div>
        </div>
    </div>
@endsection
