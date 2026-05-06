@extends('layouts.app')

@section('title', 'Мои отзывы — ' . config('site.site_name'))

@section('content')
    <!-- Dashboard Header -->
    <section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1920" class="w-full h-full object-cover">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-4xl md:text-5xl font-heading font-black text-white mb-4">Мои <span class="text-primary">отзывы</span></h1>
            <p class="text-slate-400 font-medium">История ваших отзывов и их статус модерации.</p>
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
                    @if($reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-xl transition-all">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex-shrink-0">
                                                <img src="{{ $review->dealership?->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=100' }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <h3 class="font-heading font-black text-slate-900">{{ $review->dealership?->title ?? 'Удаленный автосалон' }}</h3>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $review->created_at->format('d.m.Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $review->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($review->status === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                                {{ $review->status === 'approved' ? 'Опубликован' : ($review->status === 'pending' ? 'На модерации' : 'Отклонен') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex text-amber-400 mb-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>

                                    <p class="text-slate-600 leading-relaxed italic">"{{ $review->text }}"</p>
                                    
                                    @if($review->dealership)
                                        <div class="mt-6 pt-6 border-t border-slate-100">
                                            <a href="{{ route('dealerships.show', $review->dealership) }}" class="text-xs font-black uppercase tracking-widest text-primary hover:underline">Перейти к автосалону</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-10">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="py-20 text-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <h3 class="font-heading font-black text-slate-900 text-xl mb-2">Вы пока не оставляли отзывы.</h3>
                            <p class="text-slate-400 mb-8 max-w-sm mx-auto">Ваше мнение важно для других пользователей! Поделитесь своим опытом посещения автосалонов.</p>
                            <a href="{{ route('dealerships.index') }}" class="inline-flex bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Написать отзыв</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
