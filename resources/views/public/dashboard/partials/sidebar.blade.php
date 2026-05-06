<nav class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24 space-y-2">
    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-sm transition-all {{ request()->routeIs('dashboard.index') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Обзор
    </a>
    <a href="{{ route('dashboard.reviews') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-sm transition-all {{ request()->routeIs('dashboard.reviews') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        Мои отзывы
    </a>
    <a href="{{ route('dashboard.favorites') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-sm transition-all {{ request()->routeIs('dashboard.favorites') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        Избранное
    </a>
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl font-black text-sm transition-all text-slate-600 hover:bg-slate-50 hover:text-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        Настройки
    </a>
    <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-slate-50">
        @csrf
        <button type="submit" class="flex items-center gap-4 w-full px-6 py-4 rounded-2xl font-black text-sm text-rose-500 hover:bg-rose-50 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Выйти
        </button>
    </form>
</nav>
