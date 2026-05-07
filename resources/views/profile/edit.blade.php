@extends('layouts.app')

@section('title', 'Настройки профиля — ' . config('site.site_name'))

@section('content')
    <!-- Profile Header -->
    <section class="bg-slate-900 pt-32 pb-48 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-1/2 bg-gradient-to-t from-slate-900 to-transparent"></div>
            <div class="absolute top-1/2 left-1/4 w-[600px] h-[600px] bg-primary/10 blur-[100px] rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-breadcrumb :items="['Личный кабинет' => route('dashboard.index'), 'Настройки' => '']" class="mb-10 bg-white/5 border-white/10 text-white/60 inline-flex" />
            <h1 class="text-4xl md:text-6xl font-heading font-black text-white mb-6 tracking-tight">Настройки <span class="text-primary-200">профиля</span></h1>
            <p class="text-slate-400 font-medium text-lg max-w-2xl">Управляйте своими личными данными и настройками безопасности аккаунта.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            <!-- Sidebar Navigation -->
            <aside class="lg:col-span-1">
                <div class="sticky top-32">
                    @include('public.dashboard.partials.sidebar')
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-10">
                <div class="bg-white p-10 md:p-16 rounded-[4rem] shadow-premium border border-slate-100">
                    <div class="max-w-2xl">
                        <div class="mb-10">
                            <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight">Личная информация</h3>
                            <p class="text-slate-400 text-sm mt-2">Обновите имя и адрес электронной почты вашего профиля.</p>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white p-10 md:p-16 rounded-[4rem] shadow-premium border border-slate-100">
                    <div class="max-w-2xl">
                        <div class="mb-10">
                            <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight">Безопасность</h3>
                            <p class="text-slate-400 text-sm mt-2">Убедитесь, что ваш аккаунт использует длинный и сложный пароль.</p>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-rose-50/50 p-10 md:p-16 rounded-[4rem] border border-rose-100/50">
                    <div class="max-w-2xl">
                        <div class="mb-10">
                            <h3 class="text-2xl font-heading font-black text-rose-900 tracking-tight">Удаление аккаунта</h3>
                            <p class="text-rose-900/60 text-sm mt-2">После удаления вашего аккаунта все его ресурсы и данные будут удалены безвозвратно.</p>
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

