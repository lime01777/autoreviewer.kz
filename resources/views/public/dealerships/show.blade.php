@extends('layouts.app')

@section('title', $dealership->seo_title ?? ($dealership->title . ' — Отзывы, контакты, адрес в ' . $dealership->city))
@section('description', $dealership->seo_description ?? $dealership->short_description)

@section('meta')
    <meta property="og:title" content="{{ $dealership->title }}">
    <meta property="og:description" content="{{ $dealership->short_description }}">
    <meta property="og:image" content="{{ $dealership->logo ?? asset('images/og-default.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="autoreviewer">
    
    @php
        $schemaType = 'LocalBusiness';
        $typeMapping = [
            'official_dealer' => 'AutoDealer',
            'dealership' => 'AutoDealer',
            'shop' => 'AutoPartsStore',
            'used' => 'AutoDealer',
            'service' => 'AutoRepair',
            'parts' => 'AutoPartsStore',
        ];
        $schemaType = $typeMapping[$dealership->type] ?? 'LocalBusiness';

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $schemaType,
            'name' => $dealership->title,
            'image' => $dealership->logo,
            '@id' => url()->current(),
            'url' => $dealership->website,
            'telephone' => $dealership->phone,
            'email' => $dealership->email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $dealership->address,
                'addressLocality' => $dealership->city,
                'addressCountry' => 'KZ',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $dealership->latitude ?? 0,
                'longitude' => $dealership->longitude ?? 0,
            ],
        ];

        if ($dealership->reviews_count > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $dealership->rating_avg,
                'reviewCount' => $dealership->reviews_count,
            ];
            
            $reviewsSchema = [];
            foreach ($dealership->approvedReviews()->take(5)->get() as $review) {
                $reviewsSchema[] = [
                    '@type' => 'Review',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $review->author_name,
                    ],
                    'datePublished' => $review->created_at->format('Y-m-d'),
                    'reviewBody' => Str::limit($review->text, 150),
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'ratingValue' => $review->rating,
                    ],
                ];
            }
            $schema['review'] = $reviewsSchema;
        }

        $typeLabels = [
            'official_dealer' => 'Официальный дилер',
            'dealership' => 'Автосалон',
            'shop' => 'Автомагазин',
            'used' => 'Авто с пробегом',
            'service' => 'Сервис',
            'parts' => 'Запчасти',
        ];
        $currentTypeLabel = $typeLabels[$dealership->type] ?? 'Автосалон';
    @endphp
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
    <!-- Cover & Logo Header -->
    <section class="relative h-[400px] md:h-[550px] w-full bg-slate-900 overflow-hidden">
        <img src="{{ $dealership->cover_image_url }}" alt="Cover" class="w-full h-full object-cover opacity-40 scale-105" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-t from-[#f8fafc] via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,#2563eb_0%,transparent_40%)] opacity-20"></div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-40 relative z-10">
        <!-- Breadcrumbs -->
        <x-breadcrumb :items="['Каталог' => route('dealerships.index'), $dealership->title => '']" class="mb-10 bg-white/10 backdrop-blur-md border-white/20 text-white/70" />

        <!-- Main Card Header -->
        <div class="bg-white rounded-[3.5rem] p-8 md:p-14 shadow-premium border border-slate-100 mb-16 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-[80px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
            
            <div class="flex flex-col md:flex-row gap-10 items-start md:items-center relative z-10">
                <div class="h-48 w-48 rounded-[2.5rem] bg-white p-3 shadow-xl border border-slate-50 flex-shrink-0 -mt-24 md:-mt-40 overflow-hidden group">
                    <img src="{{ $dealership->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-[2rem] group-hover:scale-110 transition duration-700" loading="lazy">
                </div>
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="px-4 py-1.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg">{{ $currentTypeLabel }}</span>
                        @foreach($dealership->categories as $cat)
                            <span class="px-4 py-1.5 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-xl border border-primary/10">{{ $cat->title }}</span>
                        @endforeach
                        @if($dealership->is_official_dealer)
                            <span class="px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Официальный дилер
                            </span>
                        @endif
                        @if($dealership->data_verified)
                            <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-blue-100 flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.355r-1.158-1.96m0 0A9.956 9.956 0 0112 2.044a9.956 9.956 0 011.158 17.351m-1.158 1.96l1.158-1.96z"></path></svg>
                                Проверено
                            </span>
                        @endif
                        @if($dealership->is_featured)
                            <span class="px-4 py-1.5 bg-accent/10 text-accent-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-accent/10">Рекомендуем</span>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-6xl font-heading font-black text-slate-900 mb-2 tracking-tight leading-tight">{{ $dealership->title }}</h1>
                    @if($dealership->legal_name)
                        <p class="text-slate-400 font-bold text-sm mb-6">{{ $dealership->legal_name }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="flex text-amber-400">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= floor($dealership->rating_avg) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-lg font-black text-slate-900">{{ number_format($dealership->rating_avg, 1) }}</span>
                            <span class="text-slate-400 font-bold text-sm">({{ $dealership->reviews_count }} отзывов)</span>
                        </div>
                        <div class="h-6 w-px bg-slate-100 hidden md:block"></div>
                        <div class="flex items-center gap-3 text-slate-500 font-bold text-sm">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            </div>
                            {{ $dealership->city }}, {{ $dealership->address }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <x-favorite-button :dealership="$dealership" :isFull="true" class="py-5" />
                    <a href="#review-form" class="bg-slate-900 text-white px-12 py-5 rounded-[1.5rem] font-black text-sm hover:bg-primary transition shadow-xl-premium text-center">Написать отзыв</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 pb-32">
            <!-- Left Column: Info & Reviews -->
            <div class="lg:col-span-2 space-y-20">
                <!-- About -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-10 bg-primary rounded-full"></div>
                        <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 tracking-tight">Об организации</h2>
                    </div>

                    @if($dealership->brands && count($dealership->brands) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($dealership->brands as $brand)
                                <span class="px-4 py-2 bg-slate-50 text-slate-600 text-xs font-black uppercase tracking-widest rounded-xl border border-slate-100">{{ $brand }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-lg">
                        {!! nl2br(e($dealership->full_description)) !!}
                    </div>

                    @if($dealership->source_url)
                        <div class="pt-8 mt-8 border-t border-slate-50 flex flex-wrap items-center gap-6">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Источник данных:</span>
                            <a href="{{ $dealership->source_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                {{ $dealership->source_name ?? 'Официальный сайт' }}
                            </a>
                            @if($dealership->source_checked_at)
                                <span class="text-[10px] font-bold text-slate-300">Обновлено: {{ $dealership->source_checked_at->format('d.m.Y') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Review Stats -->
                <div class="bg-slate-900 rounded-[3.5rem] p-10 md:p-16 text-white relative overflow-hidden shadow-xl-premium">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-primary/20 blur-[100px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
                        <div class="md:col-span-4 text-center md:text-left">
                            <div class="text-8xl font-heading font-black text-primary-200 mb-4 leading-none">{{ number_format($dealership->rating_avg, 1) }}</div>
                            <div class="flex text-amber-400 mb-4 justify-center md:justify-start">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= floor($dealership->rating_avg) ? 'fill-current' : 'opacity-20' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">на основе {{ $dealership->reviews_count }} мнений</p>
                        </div>
                        <div class="md:col-span-8 space-y-4 w-full">
                            @php
                                $stats = [
                                    5 => $dealership->reviews()->where('rating', 5)->where('status', 'approved')->count(),
                                    4 => $dealership->reviews()->where('rating', 4)->where('status', 'approved')->count(),
                                    3 => $dealership->reviews()->where('rating', 3)->where('status', 'approved')->count(),
                                    2 => $dealership->reviews()->where('rating', 2)->where('status', 'approved')->count(),
                                    1 => $dealership->reviews()->where('rating', 1)->where('status', 'approved')->count(),
                                ];
                                $total = array_sum($stats) ?: 1;
                            @endphp
                            @foreach([5, 4, 3, 2, 1] as $star)
                                <div class="flex items-center gap-6 group">
                                    <span class="text-xs font-black w-4 text-slate-400 group-hover:text-white transition-colors">{{ $star }}</span>
                                    <div class="flex-grow h-2.5 bg-white/5 rounded-full overflow-hidden border border-white/5 p-0.5">
                                        <div class="h-full bg-primary rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(37,99,235,0.5)]" style="width: {{ ($stats[$star] / $total) * 100 }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-black w-12 group-hover:text-primary-200 transition-colors">{{ round(($stats[$star] / $total) * 100) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-12" id="reviews">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-3 h-10 bg-primary rounded-full"></div>
                            <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 tracking-tight">Отзывы покупателей</h2>
                        </div>
                    </div>
                    
                    @if($dealership->approvedReviews->isEmpty())
                        <div class="bg-white p-20 rounded-[3.5rem] text-center border border-slate-100 shadow-premium">
                            <div class="w-48 h-48 mx-auto mb-8 opacity-50">
                                <img src="{{ asset('images/placeholders/empty-reviews.svg') }}" alt="No reviews" class="w-full h-full object-contain">
                            </div>
                            <p class="text-slate-400 font-bold text-lg mb-8">Отзывов пока нет. Будьте первым, кто поделится опытом!</p>
                            <a href="#review-form" class="inline-flex bg-primary text-white px-10 py-5 rounded-[1.5rem] font-black text-sm shadow-xl shadow-primary/20">Написать отзыв</a>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach($dealership->approvedReviews as $review)
                                <div class="bg-white p-10 md:p-12 rounded-[3rem] shadow-premium border border-slate-100 hover:shadow-2xl transition duration-500 relative group">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
                                        <div class="flex items-center gap-6">
                                            <div class="h-16 w-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-2xl shadow-xl shadow-slate-200 relative overflow-hidden">
                                                <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-transparent"></div>
                                                <span class="relative z-10">{{ substr($review->author_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-heading font-black text-slate-900 text-xl tracking-tight">{{ $review->author_name }}</div>
                                                <div class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $review->created_at->format('d.m.Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="bg-primary/5 px-5 py-3 rounded-2xl flex items-center gap-2 border border-primary/10">
                                                <div class="flex text-amber-400">
                                                    @for($i=1; $i<=5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    @endfor
                                                </div>
                                                <span class="text-lg font-black text-primary">{{ $review->rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="relative mb-10">
                                        <svg class="absolute -top-6 -left-6 w-12 h-12 text-slate-50 group-hover:text-primary/5 transition-colors" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-2.2 1.8-4 4-4V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"></path></svg>
                                        <blockquote class="text-slate-700 text-lg leading-relaxed font-medium italic relative z-10 pl-4 border-l-4 border-primary/10">
                                            "{{ $review->text }}"
                                        </blockquote>
                                    </div>

                                    @if($review->pros || $review->cons)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-10 border-t border-slate-50">
                                            @if($review->pros)
                                                <div class="bg-emerald-50/30 p-6 rounded-3xl border border-emerald-100/50">
                                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-4">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        Преимущества
                                                    </div>
                                                    <p class="text-sm text-slate-600 leading-relaxed font-semibold">{{ $review->pros }}</p>
                                                </div>
                                            @endif
                                            @if($review->cons)
                                                <div class="bg-rose-50/30 p-6 rounded-3xl border border-rose-100/50">
                                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-rose-600 mb-4">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Недостатки
                                                    </div>
                                                    <p class="text-sm text-slate-600 leading-relaxed font-semibold">{{ $review->cons }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Review Form -->
                <div id="review-form" class="bg-white p-10 md:p-16 rounded-[3.5rem] shadow-premium border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary via-accent to-primary-800"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 mb-2 tracking-tight">Поделитесь опытом</h2>
                        <p class="text-slate-400 mb-10 font-medium">Ваше мнение поможет другим водителям выбрать правильный автосалон.</p>

                        @if(session('success'))
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-8 py-6 rounded-[2rem] mb-10 flex items-start gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="font-black text-lg leading-tight">Отзыв отправлен!</p>
                                    <p class="text-sm mt-1 text-emerald-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-8 py-6 rounded-[2rem] mb-10">
                                <p class="font-black text-sm mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Пожалуйста, исправьте ошибки:
                                </p>
                                <ul class="space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="text-sm flex items-start gap-2">
                                            <span class="text-rose-400 mt-0.5">—</span>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('reviews.store', $dealership) }}" method="POST" class="space-y-8" id="review-form-el">
                            @csrf
                            {{-- Honeypot spam protection --}}
                            <div class="hidden" aria-hidden="true"><input type="text" name="website_url" tabindex="-1" autocomplete="off"></div>

                            {{-- ── Row 1: Name + Star Rating ──────────────────── --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label for="author_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Ваше имя или псевдоним <span class="text-primary">*</span>
                                    </label>
                                    <input
                                        id="author_name"
                                        type="text" name="author_name"
                                        value="{{ old('author_name', auth()->user()?->name ?? '') }}"
                                        required
                                        placeholder="Как к вам обращаться?"
                                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 {{ $errors->has('author_name') ? 'border-rose-300 bg-rose-50/30' : 'border-transparent' }} focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 font-bold transition-all"
                                    >
                                    @error('author_name')
                                        <p class="text-rose-500 text-xs font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Interactive star rating --}}
                                <div class="space-y-3" x-data="{ rating: {{ old('rating', 0) }}, hover: 0 }">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Ваша оценка <span class="text-primary">*</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <template x-for="star in 5" :key="star">
                                            <button
                                                type="button"
                                                @click="rating = star"
                                                @mouseenter="hover = star"
                                                @mouseleave="hover = 0"
                                                class="focus:outline-none transition-transform hover:scale-125"
                                                :aria-label="'Оценить ' + star + ' звезд'"
                                            >
                                                <svg
                                                    class="w-9 h-9 transition-colors"
                                                    :class="star <= (hover || rating) ? 'text-amber-400' : 'text-slate-200'"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                        <span class="text-sm font-black text-slate-400 ml-2" x-text="rating ? ['', 'Ужасно', 'Плохо', 'Средне', 'Хорошо', 'Отлично'][rating] : 'Нажмите на звезду'"></span>
                                    </div>
                                    <input type="hidden" name="rating" :value="rating" required>
                                    @error('rating')
                                        <p class="text-rose-500 text-xs font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- ── Review text ──────────────────────────────── --}}
                            <div class="space-y-3" x-data="{ count: {{ strlen(old('text', '')) }} }">
                                <div class="flex justify-between items-baseline">
                                    <label for="review_text" class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Текст отзыва <span class="text-primary">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold" :class="count < 20 ? 'text-rose-400' : 'text-slate-300'">
                                        <span x-text="count"></span>/3000
                                    </span>
                                </div>
                                <textarea
                                    id="review_text"
                                    name="text" rows="5" required
                                    @input="count = $event.target.value.length"
                                    placeholder="Расскажите подробнее: покупка, сервис, отношение персонала, цены, нюансы..."
                                    class="w-full px-6 py-5 rounded-2xl bg-slate-50 border-2 {{ $errors->has('text') ? 'border-rose-300 bg-rose-50/30' : 'border-transparent' }} focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 font-medium transition-all"
                                >{{ old('text') }}</textarea>
                                @error('text')
                                    <p class="text-rose-500 text-xs font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- ── Pros / Cons ───────────────────────────────── --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label for="pros" class="block text-[10px] font-black uppercase tracking-widest text-emerald-500">
                                        Что понравилось? <span class="text-slate-300">(необязательно)</span>
                                    </label>
                                    <textarea id="pros" name="pros" rows="3"
                                        placeholder="Вежливый персонал, хорошие цены, быстрое оформление..."
                                        class="w-full px-6 py-4 rounded-2xl bg-emerald-50/50 border-2 border-transparent focus:bg-white focus:border-emerald-400/30 focus:ring-4 focus:ring-emerald-400/5 font-medium transition-all"
                                    >{{ old('pros') }}</textarea>
                                    @error('pros') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="cons" class="block text-[10px] font-black uppercase tracking-widest text-rose-500">
                                        Что расстроило? <span class="text-slate-300">(необязательно)</span>
                                    </label>
                                    <textarea id="cons" name="cons" rows="3"
                                        placeholder="Долгое ожидание, скрытые доплаты, проблемы с документами..."
                                        class="w-full px-6 py-4 rounded-2xl bg-rose-50/50 border-2 border-transparent focus:bg-white focus:border-rose-400/30 focus:ring-4 focus:ring-rose-400/5 font-medium transition-all"
                                    >{{ old('cons') }}</textarea>
                                    @error('cons') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- ── Contact details (optional) ───────────────── --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label for="author_email" class="block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Email <span class="text-slate-300">(необязательно, не публикуется)</span>
                                    </label>
                                    <input id="author_email" type="email" name="author_email"
                                        value="{{ old('author_email', auth()->user()?->email ?? '') }}"
                                        placeholder="your@email.com"
                                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 {{ $errors->has('author_email') ? 'border-rose-300' : 'border-transparent' }} focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 font-medium transition-all"
                                    >
                                    @error('author_email') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-3">
                                    <label for="author_phone" class="block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Телефон <span class="text-slate-300">(необязательно, не публикуется)</span>
                                    </label>
                                    <input id="author_phone" type="tel" name="author_phone"
                                        value="{{ old('author_phone') }}"
                                        placeholder="+7 XXX XXX XX XX"
                                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 {{ $errors->has('author_phone') ? 'border-rose-300' : 'border-transparent' }} focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 font-medium transition-all"
                                    >
                                    @error('author_phone') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- ── Agree rules + Submit ─────────────────────── --}}
                            <div class="pt-6 border-t border-slate-50 space-y-6">
                                <label class="flex items-start gap-4 cursor-pointer group">
                                    <input type="checkbox" name="agree_rules" value="1"
                                        {{ old('agree_rules') ? 'checked' : '' }}
                                        class="mt-1 w-5 h-5 rounded-lg text-primary focus:ring-primary/20 cursor-pointer flex-shrink-0"
                                    >
                                    <span class="text-sm text-slate-500 font-medium leading-relaxed group-hover:text-slate-700 transition-colors">
                                        Я подтверждаю, что лично посетил(а) данный автосалон, и мой отзыв является достоверным.
                                        Я согласен(а) с <a href="#" class="text-primary font-bold hover:underline">правилами публикации отзывов</a>.
                                    </span>
                                </label>
                                @error('agree_rules')
                                    <p class="text-rose-500 text-xs font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                                    <p class="text-xs text-slate-400 max-w-sm">
                                        Отзыв пройдет модерацию и появится на сайте в течение 24 часов.
                                    </p>
                                    <button type="submit"
                                        class="w-full sm:w-auto bg-primary text-white px-12 py-5 rounded-2xl font-black text-base hover:bg-slate-900 transition-all shadow-xl shadow-primary/20 hover:-translate-y-0.5 flex items-center justify-center gap-3"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Отправить отзыв
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-12">
                <!-- Contact Card -->
                <div class="bg-white p-10 rounded-[3.5rem] shadow-premium border border-slate-100 overflow-hidden relative group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 group-hover:bg-primary/10 transition-colors blur-[40px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <h3 class="text-2xl font-heading font-black text-slate-900 mb-10 tracking-tight">Контакты</h3>
                    <div class="space-y-8">
                        <div class="flex items-start gap-5 group/item">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 group-hover/item:bg-primary/10 group-hover/item:text-primary flex items-center justify-center flex-shrink-0 text-slate-400 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Адрес компании</p>
                                <p class="text-sm font-bold text-slate-900 leading-relaxed">{{ $dealership->address }}, {{ $dealership->city }}</p>
                            </div>
                        </div>
                        
                        @if($dealership->phone)
                            <div class="flex items-start gap-5 group/item">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 group-hover/item:bg-primary/10 group-hover/item:text-primary flex items-center justify-center flex-shrink-0 text-slate-400 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Телефон</p>
                                    <a href="tel:{{ $dealership->phone }}" class="text-base font-black text-slate-900 hover:text-primary transition-colors">{{ $dealership->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($dealership->whatsapp)
                            <div class="flex items-start gap-5 group/item">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 transition-all">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">WhatsApp</p>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dealership->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="text-base font-black text-slate-900 hover:text-emerald-500 transition-colors">Написать в чат</a>
                                </div>
                            </div>
                        @endif

                        @if($dealership->email)
                            <div class="flex items-start gap-5 group/item">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email</p>
                                    <a href="mailto:{{ $dealership->email }}" class="text-sm font-bold text-slate-900 hover:text-primary transition-colors">{{ $dealership->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($dealership->instagram)
                            <div class="flex items-start gap-5 group/item">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0 transition-all">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Instagram</p>
                                    <a href="{{ $dealership->instagram }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-slate-900 hover:text-rose-500 transition-colors">@instagram_profile</a>
                                </div>
                            </div>
                        @endif

                        @if($dealership->website)
                            <a href="{{ $dealership->website }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-3 w-full py-5 rounded-[1.5rem] bg-slate-900 text-white font-black text-sm hover:bg-primary transition-all shadow-xl shadow-slate-200 group/btn">
                                Перейти на сайт
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Working Hours -->
                @if($dealership->working_hours)
                    <div class="bg-white p-10 rounded-[3.5rem] shadow-premium border border-slate-100 relative group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-accent/5 group-hover:bg-accent/10 transition-colors blur-[30px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                        <h3 class="text-2xl font-heading font-black text-slate-900 mb-10 tracking-tight">Режим работы</h3>
                        <div class="space-y-5">
                            @if(is_array($dealership->working_hours))
                                @foreach($dealership->working_hours as $day => $hours)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">{{ $day }}</span>
                                        <span class="font-black text-slate-900">{{ $hours }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-slate-600 leading-relaxed font-bold">{{ $dealership->working_hours }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Map -->
                @if($dealership->latitude && $dealership->longitude)
                    <div class="bg-white rounded-[3.5rem] shadow-premium border border-slate-100 overflow-hidden h-80 relative group">
                        <div id="map" class="h-full w-full grayscale-[0.5] group-hover:grayscale-0 transition-all duration-700"></div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var map = L.map('map', {zoomControl: false}).setView([{{ $dealership->latitude }}, {{ $dealership->longitude }}], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap'
                                }).addTo(map);
                                var customIcon = L.divIcon({
                                    className: 'custom-div-icon',
                                    html: "<div style='background-color:#2563eb; width:20px; height:20px; border-radius:50%; border:4px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3)'></div>",
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                });
                                L.marker([{{ $dealership->latitude }}, {{ $dealership->longitude }}], {icon: customIcon}).addTo(map);
                            });
                        </script>
                    </div>
                @endif

                <!-- Sidebar Banner -->
                <div class="rounded-[3rem] overflow-hidden shadow-premium">
                    <x-banner position="dealership_sidebar" />
                </div>

                <!-- Similar -->
                @if($similarDealerships->isNotEmpty())
                    <div class="space-y-8">
                        <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight">Похожие дилеры</h3>
                        <div class="space-y-4">
                            @foreach($similarDealerships as $sim)
                                <a href="{{ route('dealerships.show', $sim) }}" class="flex items-center gap-5 p-4 rounded-3xl bg-white border border-slate-100 hover:border-primary/20 hover:shadow-xl transition-all duration-300 group">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-lg flex-shrink-0 relative">
                                        <img src="{{ $sim->logo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" loading="lazy">
                                        <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-colors"></div>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-black text-slate-900 group-hover:text-primary transition-colors line-clamp-1 leading-tight">{{ $sim->title }}</h4>
                                        <div class="flex items-center gap-3 mt-2">
                                            <div class="flex text-amber-400">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </div>
                                            <span class="text-xs font-black text-slate-400">{{ number_format($sim->rating_avg, 1) }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
