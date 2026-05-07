@props(['dealership'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl transition duration-500 group flex flex-col h-full']) }}>
    <!-- Cover Image -->
    <div class="relative h-64 overflow-hidden group">
        <img 
            src="{{ $dealership->cover_image_url }}" 
            alt="{{ $dealership->title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
        
        <div class="absolute top-6 left-6 flex flex-col gap-2">
            @if($dealership->is_official_dealer)
                <div class="bg-emerald-500 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    ДИЛЕР
                </div>
            @endif
            @if($dealership->data_verified)
                <div class="bg-blue-600/90 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg backdrop-blur-sm flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r-1.158-1.96m0 0A9.956 9.956 0 0112 2.044a9.956 9.956 0 011.158 17.351m-1.158 1.96l1.158-1.96z"></path></svg>
                    ПРОВЕРЕНО
                </div>
            @endif
            @if($dealership->is_featured)
                <div class="bg-accent text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg backdrop-blur-sm bg-accent/90">
                    РЕКОМЕНДУЕМ
                </div>
            @endif
        </div>

        <div class="absolute bottom-6 left-6 flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white p-1.5 shadow-xl border border-white/20 overflow-hidden">
                <img src="{{ $dealership->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-xl" loading="lazy">
            </div>
            <div>
                <h3 class="text-white font-heading font-black text-xl leading-tight group-hover:text-primary-100 transition-colors">{{ $dealership->title }}</h3>
                <p class="text-white/70 text-xs font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $dealership->city }}, {{ $dealership->address }}
                </p>
            </div>
        </div>
        
        <div class="absolute top-6 right-6">
            <x-favorite-button :dealership="$dealership" />
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-8 flex flex-col flex-grow">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <div class="bg-primary/5 px-3 py-1.5 rounded-xl flex items-center gap-1.5 border border-primary/10">
                    <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span class="text-sm font-black text-primary">{{ number_format($dealership->rating_avg, 1) }}</span>
                </div>
                <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $dealership->reviews_count }} отзывов</span>
            </div>
            
            <div class="flex -space-x-2">
                @for($i=0; $i<3; $i++)
                    <div class="w-6 h-6 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img src="https://i.pravatar.cc/100?u={{ $dealership->id + $i }}" class="w-full h-full object-cover" alt="User avatar">
                    </div>
                @endfor
            </div>
        </div>
        
        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2 italic">"{{ $dealership->short_description }}"</p>
        
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            <span class="text-sm font-bold text-slate-600">{{ $dealership->phone }}</span>
        </div>

        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
            <div class="flex flex-wrap gap-2">
                @foreach($dealership->categories->take(2) as $category)
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-lg">{{ $category->title }}</span>
                @endforeach
            </div>
            
            <a href="{{ route('dealerships.show', $dealership) }}" class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary group/btn">
                Смотреть
                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</div>
