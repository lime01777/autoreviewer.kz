@extends('layouts.app')

@section('title', $news->seo_title ?? ($news->title . ' - ' . config('site.site_name')))
@section('description', $news->excerpt)

@section('meta')
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ $news->excerpt }}">
    <meta property="og:image" content="{{ $news->image ?? asset('images/og-news.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    @php
        $newsSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'image' => [
                $news->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1200'
            ],
            'datePublished' => $news->published_at->toAtomString(),
            'dateModified' => $news->updated_at->toAtomString(),
            'author' => [
                [
                    '@type' => 'Organization',
                    'name' => config('site.site_name'),
                    'url' => route('home'),
                ]
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($newsSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <!-- Breadcrumbs -->
        <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-10 gap-2 items-center">
            <a href="/" class="hover:text-primary transition-colors">Главная</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('news.index') }}" class="hover:text-primary transition-colors">Новости</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 line-clamp-1">Статья</span>
        </nav>

        <header class="mb-16">
            <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-6">
                <span>{{ $news->published_at->format('d F Y') }}</span>
                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                <span>5 мин чтения</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-heading font-black text-slate-900 leading-tight mb-10 tracking-tight">
                {{ $news->title }}
            </h1>
            <div class="relative h-[500px] rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200/50">
                <img src="{{ $news->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1200' }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
            </div>
        </header>

        <div class="prose prose-xl prose-slate max-w-none prose-headings:font-heading prose-headings:font-black prose-p:leading-relaxed prose-p:text-slate-600 prose-a:text-primary prose-img:rounded-[2rem]">
            {!! $news->content !!}
        </div>

        <footer class="mt-20 pt-10 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">Поделиться:</span>
                    <div class="flex gap-3">
                        @foreach(['twitter', 'facebook', 'vk'] as $social)
                            <button class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all shadow-sm">
                                <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm font-black text-slate-900 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Назад к списку
                </a>
            </div>
        </footer>
    </article>

    <!-- More News -->
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-heading font-black text-slate-900 mb-12">Читайте также</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($recentNews as $recent)
                    <a href="{{ route('news.show', $recent) }}" class="group">
                        <div class="h-48 rounded-[2rem] overflow-hidden mb-6 shadow-lg">
                            <img src="{{ $recent->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=400' }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <h3 class="font-heading font-black text-slate-900 group-hover:text-primary transition-colors line-clamp-2 leading-tight">{{ $recent->title }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
