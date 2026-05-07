<nav class="bg-white p-6 md:p-10 rounded-[3.5rem] shadow-premium border border-slate-100 space-y-3">
    <div class="mb-8 ml-4">
        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300">Навигация</h4>
    </div>
    
    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('dashboard.index') ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-[1.02]' : 'text-slate-500 hover:bg-slate-50 hover:text-primary hover:translate-x-1' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Обзор</span>
    </a>
    
    <a href="{{ route('dashboard.reviews') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('dashboard.reviews') ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-[1.02]' : 'text-slate-500 hover:bg-slate-50 hover:text-primary hover:translate-x-1' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <span>Мои отзывы</span>
    </a>
    
    <a href="{{ route('dashboard.favorites') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('dashboard.favorites') ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-[1.02]' : 'text-slate-500 hover:bg-slate-50 hover:text-primary hover:translate-x-1' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        <span>Избранное</span>
    </a>
    
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('profile.edit') ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-[1.02]' : 'text-slate-500 hover:bg-slate-50 hover:text-primary hover:translate-x-1' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span>Настройки</span>
    </a>
    
    @if(auth()->user()->isAdmin())
    <div class="pt-4 mt-4 border-t border-slate-100">
        <a href="/admin" target="_blank"
           class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-500/30 hover:translate-x-1 group/admin">
            <svg class="w-5 h-5 group-hover/admin:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                </path>
            </svg>
            <span>Панель администратора</span>
            <svg class="w-3.5 h-3.5 ml-auto opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
        </a>
    </div>
    @endif

    <div class="pt-8 mt-8 border-t border-slate-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-4 w-full px-6 py-5 rounded-2xl font-black text-xs uppercase tracking-widest text-rose-500 hover:bg-rose-50 transition-all duration-300 group/logout">
                <svg class="w-5 h-5 group-hover/admin:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Выйти</span>
            </button>
        </form>
    </div>
</nav>

