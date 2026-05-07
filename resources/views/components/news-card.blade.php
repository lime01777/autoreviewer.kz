@props(['news'])

<article {{ $attributes->merge(['class' => 'group flex flex-col h-full']) }}>
    <a href="{{ route('news.show', $news) }}" class="block flex-grow">
        <div class="relative h-64 rounded-[2.5rem] overflow-hidden mb-6 shadow-xl shadow-slate-200/40 border border-slate-100">
            <img src="{{ $news->image_url }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700" 
                 alt="{{ $news->title }}"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div class="absolute top-6 left-6 flex gap-2">
                <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm">
                    {{ $news->published_at->format('d.m.Y') }}
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-3 mb-4">
            <span class="w-2 h-2 bg-primary rounded-full"></span>
            <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ $news->category ?? 'Авторынок' }}</span>
        </div>
        
        <h3 class="text-xl md:text-2xl font-heading font-black text-slate-900 group-hover:text-primary transition-colors line-clamp-2 leading-tight mb-4">
            {{ $news->title }}
        </h3>
        
        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-6">
            {{ $news->excerpt }}
        </p>
    </a>
    
    <div class="pt-6 border-t border-slate-50 flex items-center justify-between mt-auto">
        <div class="flex items-center gap-2 text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">3 мин на чтение</span>
        </div>
        
        <a href="{{ route('news.show', $news) }}" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>
</article>
