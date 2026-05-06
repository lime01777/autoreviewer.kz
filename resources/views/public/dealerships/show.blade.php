@extends('layouts.app')

@section('title', $dealership->seo_title ?? ($dealership->title . ' - Отзывы, контакты, адрес'))
@section('description', $dealership->seo_description ?? $dealership->short_description)

@section('meta')
    <meta property="og:title" content="{{ $dealership->title }}">
    <meta property="og:description" content="{{ $dealership->short_description }}">
    <meta property="og:image" content="{{ $dealership->logo ?? asset('images/og-default.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('site.site_name') }}">
    
    @php
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $dealership->title,
            'image' => $dealership->logo,
            '@id' => url()->current(),
            'url' => $dealership->website,
            'telephone' => $dealership->phone,
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
            
            $reviews = [];
            foreach ($dealership->reviews()->where('status', 'approved')->take(5)->get() as $review) {
                $reviews[] = [
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
            $schema['review'] = $reviews;
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection


@section('content')
    <!-- Cover & Logo Header -->
    <section class="relative h-[400px] md:h-[500px] w-full bg-slate-900 overflow-hidden">
        <img src="{{ $dealership->cover_image ?? 'https://images.unsplash.com/photo-1562141989-c5c79ac8f576?auto=format&fit=crop&q=80&w=1920' }}" alt="Cover" class="w-full h-full object-cover opacity-40 scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-[#fcfdfe] via-slate-900/40 to-transparent"></div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-32 relative z-10">
        <!-- Main Card Header -->
        <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-slate-200/60 border border-slate-100 mb-12">
            <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                <div class="h-48 w-48 rounded-[2rem] bg-white p-3 shadow-2xl border border-slate-50 flex-shrink-0 -mt-24 md:-mt-32 overflow-hidden">
                    <img src="{{ $dealership->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=300' }}" alt="Logo" class="w-full h-full object-cover rounded-[1.5rem]">
                </div>
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @foreach($dealership->categories as $cat)
                            <span class="px-3 py-1 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg border border-primary/10">{{ $cat->title }}</span>
                        @endforeach
                    </div>
                    <h1 class="text-3xl md:text-5xl font-heading font-black text-slate-900 mb-4 tracking-tight">{{ $dealership->title }}</h1>
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2">
                            <x-rating-stars :rating="$dealership->rating_avg" :count="$dealership->reviews_count" class="text-accent" />
                        </div>
                        <div class="h-4 w-px bg-slate-200 hidden md:block"></div>
                        <div class="flex items-center gap-2 text-slate-500 font-bold text-sm">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $dealership->city }}
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <x-favorite-button :dealership="$dealership" :isFull="true" />
                    <a href="#review-form" class="flex-grow md:flex-grow-0 bg-primary text-white px-10 py-4 rounded-2xl font-black text-sm hover:bg-primary-600 transition shadow-lg shadow-primary/20 text-center">Оставить отзыв</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 pb-24">
            <!-- Left Column: Info & Reviews -->
            <div class="lg:col-span-2 space-y-12">
                <!-- About -->
                <div class="space-y-6">
                    <h2 class="text-2xl md:text-3xl font-heading font-black text-slate-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-primary rounded-full"></span>
                        Об организации
                    </h2>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-lg">
                        {!! nl2br(e($dealership->full_description)) !!}
                    </div>
                </div>

                <!-- Review Stats -->
                <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 blur-[80px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                        <div class="text-center md:text-left">
                            <div class="text-7xl font-heading font-black text-primary-600 mb-2">{{ $dealership->rating_avg }}</div>
                            <div class="flex text-accent mb-2 justify-center md:justify-start">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-5 h-5 {{ $i < floor($dealership->rating_avg) ? 'fill-current' : 'opacity-30' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">на основе {{ $dealership->reviews_count }} отзывов</p>
                        </div>
                        <div class="flex-grow space-y-3 w-full">
                            @php
                                $stats = [
                                    5 => $dealership->reviews()->where('rating', 5)->count(),
                                    4 => $dealership->reviews()->where('rating', 4)->count(),
                                    3 => $dealership->reviews()->where('rating', 3)->count(),
                                    2 => $dealership->reviews()->where('rating', 2)->count(),
                                    1 => $dealership->reviews()->where('rating', 1)->count(),
                                ];
                                $total = array_sum($stats) ?: 1;
                            @endphp
                            @foreach([5, 4, 3, 2, 1] as $star)
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold w-3">{{ $star }}</span>
                                    <div class="flex-grow h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ ($stats[$star] / $total) * 100 }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-black w-8">{{ round(($stats[$star] / $total) * 100) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-8" id="reviews">
                    <h2 class="text-2xl md:text-3xl font-heading font-black text-slate-900">Последние отзывы</h2>
                    @if($dealership->reviews->isEmpty())
                        <div class="bg-white p-16 rounded-[3rem] text-center border border-slate-100 shadow-xl shadow-slate-200/40">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">Отзывов пока нет. Будьте первым!</p>
                        </div>
                    @else
                        @foreach($dealership->reviews as $review)
                            <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-2xl transition duration-500">
                                <div class="flex items-center mb-8">
                                    <div class="h-14 w-14 rounded-2xl bg-primary-900 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-primary/20">
                                        {{ substr($review->author_name, 0, 1) }}
                                    </div>
                                    <div class="ml-5">
                                        <div class="font-heading font-black text-slate-900 text-lg">{{ $review->author_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $review->created_at->format('d.m.Y') }}</div>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="bg-primary/5 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span class="text-sm font-black text-primary">{{ $review->rating }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <blockquote class="text-slate-700 text-lg leading-relaxed font-medium italic mb-8">
                                    "{{ $review->text }}"
                                </blockquote>

                                @if($review->pros || $review->cons)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-50">
                                        @if($review->pros)
                                            <div>
                                                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500 mb-3">Преимущества</div>
                                                <p class="text-sm text-slate-600 leading-relaxed font-medium">{{ $review->pros }}</p>
                                            </div>
                                        @endif
                                        @if($review->cons)
                                            <div>
                                                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 mb-3">Недостатки</div>
                                                <p class="text-sm text-slate-600 leading-relaxed font-medium">{{ $review->cons }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Review Form -->
                <div id="review-form" class="bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl shadow-slate-200/60 border border-slate-100">
                    <h2 class="text-2xl md:text-4xl font-heading font-black text-slate-900 mb-4">Оставить отзыв</h2>
                    <p class="text-slate-400 mb-10 text-lg">Поделитесь вашим опытом, чтобы помочь другим покупателям.</p>
                    
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-8 py-6 rounded-3xl mb-10 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mr-4 shadow-lg shadow-emerald-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                            <p class="font-bold">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-100 text-rose-700 px-8 py-6 rounded-3xl mb-10 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0 mr-4 shadow-lg shadow-rose-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                            <p class="font-bold">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('reviews.store', $dealership) }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="hidden"><input type="text" name="website_url"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400">Ваше имя <span class="text-primary">*</span></label>
                                <input type="text" name="author_name" value="{{ old('author_name', auth()->user()->name ?? '') }}" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold transition-all">
                                @error('author_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400">Ваша оценка <span class="text-primary">*</span></label>
                                <select name="rating" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-bold transition-all appearance-none cursor-pointer">
                                    <option value="">Выберите...</option>
                                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 — Отлично</option>
                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 — Хорошо</option>
                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 — Средне</option>
                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 — Плохо</option>
                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 — Ужасно</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400">Ваш отзыв <span class="text-primary">*</span></label>
                            <textarea name="text" rows="5" required placeholder="Напишите здесь подробности вашего визита..." class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary font-medium transition-all">{{ old('text') }}</textarea>
                            @error('text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-xs font-black uppercase tracking-widest text-emerald-500">Плюсы</label>
                                <textarea name="pros" rows="3" placeholder="Что понравилось?" class="w-full px-6 py-4 rounded-2xl bg-emerald-50/50 border-none focus:ring-2 focus:ring-emerald-500 font-medium transition-all">{{ old('pros') }}</textarea>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-xs font-black uppercase tracking-widest text-rose-500">Минусы</label>
                                <textarea name="cons" rows="3" placeholder="Что не понравилось?" class="w-full px-6 py-4 rounded-2xl bg-rose-50/50 border-none focus:ring-2 focus:ring-rose-500 font-medium transition-all">{{ old('cons') }}</textarea>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6">
                            <p class="text-xs text-slate-400 italic">Нажимая кнопку, вы соглашаетесь с правилами модерации и политикой конфиденциальности.</p>
                            <button type="submit" class="w-full md:w-auto bg-primary text-white px-12 py-5 rounded-2xl font-black text-lg hover:bg-primary-600 transition shadow-xl shadow-primary/20">Отправить отзыв</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-10">
                <!-- Contact Card -->
                <div class="bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 blur-[40px] -translate-y-1/2 translate-x-1/4 rounded-full"></div>
                    <h3 class="text-2xl font-heading font-black text-slate-900 mb-8">Контакты</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center flex-shrink-0 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Адрес</p>
                                <p class="text-sm font-bold text-slate-900">{{ $dealership->address }}, {{ $dealership->city }}</p>
                            </div>
                        </div>
                        @if($dealership->phone)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center flex-shrink-0 text-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Телефон</p>
                                    <a href="tel:{{ $dealership->phone }}" class="text-sm font-black text-slate-900 hover:text-primary transition">{{ $dealership->phone }}</a>
                                </div>
                            </div>
                        @endif
                        @if($dealership->website)
                            <a href="{{ $dealership->website }}" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-slate-50 font-bold text-sm text-slate-900 hover:bg-primary hover:text-white transition-all group">
                                Перейти на сайт
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Working Hours -->
                @if($dealership->working_hours)
                    <div class="bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100">
                        <h3 class="text-xl font-heading font-black text-slate-900 mb-8">График работы</h3>
                        <div class="space-y-4">
                            @if(is_array($dealership->working_hours))
                                @foreach($dealership->working_hours as $day => $hours)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-400 font-bold">{{ $day }}</span>
                                        <span class="font-black text-slate-900">{{ $hours }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $dealership->working_hours }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Map -->
                @if($dealership->latitude && $dealership->longitude)
                    <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden h-72 relative">
                        <div id="map" class="h-full w-full"></div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var map = L.map('map').setView([{{ $dealership->latitude }}, {{ $dealership->longitude }}], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap'
                                }).addTo(map);
                                L.marker([{{ $dealership->latitude }}, {{ $dealership->longitude }}]).addTo(map);
                            });
                        </script>
                    </div>
                @endif

                <!-- Sidebar Banner -->
                <x-banner position="dealership_sidebar" />

                <!-- Similar -->
                <div class="space-y-6">
                    <h3 class="text-xl font-heading font-black text-slate-900">Похожие компании</h3>
                    @foreach($similarDealerships as $sim)
                        <a href="{{ route('dealerships.show', $sim) }}" class="flex items-center gap-4 group">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-lg flex-shrink-0">
                                <img src="{{ $sim->logo ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=100' }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 group-hover:text-primary transition-colors line-clamp-1 leading-tight">{{ $sim->title }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <svg class="w-3 h-3 text-accent fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-xs font-black text-slate-400">{{ $sim->rating_avg }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
