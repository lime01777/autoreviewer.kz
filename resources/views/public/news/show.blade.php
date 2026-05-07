@extends('layouts.app')

@section('title', $news->seo_title ?? ($news->title . ' - ' . config('site.site_name')))
@section('description', $news->excerpt)

@section('meta')
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ $news->excerpt }}">
    <meta property="og:image" content="{{ $news->image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">

    @php
        $newsSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'image' => [
                $news->image_url
            ],
            'datePublished' => $news->published_at->toAtomString(),
            'dateModified' => $news->updated_at->toAtomString(),
            'author' => [
                [
                    '@type' => 'Organization',
                    'name' => 'autoreviewer',
                    'url' => route('home'),
                ]
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($newsSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24">
        <!-- Breadcrumbs -->
        <x-breadcrumb :items="['Новости' => route('news.index'), 'Статья' => '']" class="mb-12 bg-slate-50 border-slate-100" />

        <header class="mb-16">
            <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-8 ml-1">
                <span class="px-4 py-1.5 bg-primary/5 rounded-lg">{{ $news->published_at->format('d F Y') }}</span>
                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                <span class="text-slate-400">5 мин чтения</span>
            </div>
            <h1 class="text-4xl md:text-7xl font-heading font-black text-slate-900 leading-[1.1] mb-12 tracking-tight">
                {{ $news->title }}
            </h1>
            <div class="relative h-[400px] md:h-[600px] rounded-[3.5rem] overflow-hidden shadow-xl-premium group">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-1000" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>
        </header>

        <div class="prose prose-xl prose-slate max-w-none prose-headings:font-heading prose-headings:font-black prose-p:leading-relaxed prose-p:text-slate-600 prose-a:text-primary prose-a:font-black prose-img:rounded-[2.5rem] prose-img:shadow-premium prose-blockquote:border-primary prose-blockquote:bg-slate-50 prose-blockquote:p-8 prose-blockquote:rounded-3xl prose-blockquote:italic">
            {!! $news->content !!}
        </div>

        <footer class="mt-24 pt-12 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="flex items-center gap-6">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Поделиться</span>
                    <div class="flex gap-4">
                        @foreach(['twitter', 'facebook', 'vk'] as $social)
                            <button class="h-14 w-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary hover:-translate-y-1 transition-all shadow-premium group/social">
                                <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-3 text-xs font-black uppercase tracking-widest text-slate-900 group hover:text-primary transition-colors bg-slate-50 px-8 py-5 rounded-2xl border border-slate-100">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Ко всем новостям
                </a>
            </div>
        </footer>
    </article>

    <!-- More News -->
    <section class="bg-slate-50 py-32 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-16">
                <div class="flex items-center gap-4">
                    <div class="w-3 h-10 bg-primary rounded-full"></div>
                    <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 tracking-tight">Читайте также</h2>
                </div>
                <a href="{{ route('news.index') }}" class="hidden md:flex text-xs font-black uppercase tracking-widest text-primary border-b-2 border-primary/20 hover:border-primary transition-all pb-1">Все материалы</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @foreach($recentNews as $recent)
                    <x-news-card :news="$recent" />
                @endforeach
            </div>
        </div>
    </section>

@endsection
