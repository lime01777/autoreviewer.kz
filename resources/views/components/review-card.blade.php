@props(['review'])

<div {{ $attributes->merge(['class' => 'bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl transition duration-500 group relative']) }}>
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary font-black text-xl border border-primary/10">
                {{ substr($review->author_name, 0, 1) }}
            </div>
            <div>
                <h4 class="font-heading font-black text-slate-900 leading-tight">{{ $review->author_name }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </div>
        
        <div class="flex text-amber-400">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            @endfor
        </div>
    </div>
    
    <div class="relative">
        <svg class="absolute -top-4 -left-4 w-8 h-8 text-slate-50 opacity-50" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-2.2 1.8-4 4-4V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"></path></svg>
        <p class="text-slate-600 leading-relaxed italic relative z-10">
            "{{ $review->text }}"
        </p>
    </div>
    
    @if($review->dealership)
        <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
            <a href="{{ route('dealerships.show', $review->dealership) }}" class="flex items-center gap-2 group/link">
                <div class="w-8 h-8 rounded-lg border border-slate-100 overflow-hidden">
                    <img src="{{ $review->dealership->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=50' }}" class="w-full h-full object-cover">
                </div>
                <span class="text-xs font-bold text-slate-400 group-hover/link:text-primary transition-colors line-clamp-1">{{ $review->dealership->title }}</span>
            </a>
            
            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $review->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                {{ $review->status === 'approved' ? 'Проверен' : 'Модерация' }}
            </span>
        </div>
    @endif
</div>
