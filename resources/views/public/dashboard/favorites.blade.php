@extends('layouts.app')

@section('title', 'Избранное — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-4xl md:text-5xl font-heading font-black text-white mb-4">Избранные <span class="text-primary">автосалоны</span></h1>
            <p class="text-slate-400 font-medium">Список автосалонов, которые вы сохранили.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="lg:col-span-1">
                @include('public.dashboard.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100">
                    @if($favorites->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($favorites as $dealer)
                                <div class="group relative bg-slate-50 rounded-3xl p-6 hover:bg-white hover:shadow-2xl hover:shadow-primary/10 transition-all border border-transparent hover:border-primary/10">
                                    <div class="flex items-center gap-5">
                                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0">
                                            <img src="{{ $dealer->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=200' }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-heading font-black text-slate-900 text-lg line-clamp-1 group-hover:text-primary transition-colors">
                                                {{ $dealer->title }}
                                            </h3>
                                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $dealer->city }}</p>
                                            <div class="flex items-center gap-1 mt-2">
                                                <div class="flex text-amber-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3 h-3 {{ $i <= round($dealer->rating) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endfor
                                                </div>
                                                <span class="text-[10px] font-black text-slate-400">{{ number_format($dealer->rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('dealerships.show', $dealer) }}" class="text-xs font-black uppercase tracking-widest text-primary hover:underline">Подробнее</a>
                                            <x-favorite-button :dealership="$dealer" />
                                        </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-10">
                            {{ $favorites->links() }}
                        </div>
                    @else
                        <div class="py-20 text-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            <h3 class="font-heading font-black text-slate-900 text-xl mb-2">Вы пока не добавили автосалоны в избранное.</h3>
                            <p class="text-slate-400 mb-8 max-w-sm mx-auto">Сохраняйте интересные предложения, чтобы вернуться к ним позже.</p>
                            <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Перейти в каталог</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
