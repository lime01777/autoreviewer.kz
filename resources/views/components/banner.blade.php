@props(['position'])

@php
    $banner = \App\Models\Banner::active($position)->inRandomOrder()->first();
@endphp

@if($banner)
    <div class="banner-container my-8 overflow-hidden rounded-2xl shadow-sm border border-gray-100 bg-white group transition duration-500 hover:shadow-xl">
        <a href="{{ $banner->link ?? '#' }}" {{ $banner->link ? 'target="_blank"' : '' }} class="block relative">
            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-auto object-cover transition duration-700 group-hover:scale-[1.02]">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            @if(!$banner->link)
                <div class="absolute top-2 right-2 bg-white/80 backdrop-blur px-2 py-0.5 rounded text-[10px] font-bold text-gray-500 uppercase tracking-widest">Реклама</div>
            @endif
        </a>
    </div>
@endif
