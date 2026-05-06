@extends('layouts.app')

@section('title', 'AVTOREWIER - Отзывы об автосалонах')

@section('content')
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
            <span class="block">Найдите лучший автосалон</span>
            <span class="block text-primary">читайте честные отзывы</span>
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            AVTOREWIER — это независимый портал отзывов об автомобильных магазинах и автосалонах вашего города.
        </p>
        <div class="mt-10 flex justify-center gap-x-6">
            <a href="#" class="rounded-md bg-primary px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">Перейти к поиску</a>
            <a href="#" class="text-lg font-semibold leading-6 text-gray-900 flex items-center">Оставить отзыв <span class="ml-2" aria-hidden="true">→</span></a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Все автосалоны</h3>
            <p class="text-gray-600 text-sm">Полный список дилеров и магазинов запчастей в одном месте.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Честные оценки</h3>
            <p class="text-gray-600 text-sm">Только реальные отзывы от настоящих покупателей.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-2">Новости рынка</h3>
            <p class="text-gray-600 text-sm">Свежие новости и акции от крупнейших автоцентров.</p>
        </div>
    </div>
</div>
@endsection
