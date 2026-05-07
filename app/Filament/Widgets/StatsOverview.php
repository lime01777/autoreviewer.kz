<?php

namespace App\Filament\Widgets;

use App\Models\Dealership;
use App\Models\Review;
use App\Models\User;
use App\Models\Banner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Автосалоны', Dealership::count())
                ->description('Всего в базе')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('success'),
            Stat::make('Новые отзывы', Review::where('status', 'pending')->count())
                ->description('Ожидают модерации')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
            Stat::make('Активные баннеры', Banner::where('status', 'active')->count())
                ->description('Показываются на сайте')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('info'),
            Stat::make('Пользователи', User::count())
                ->description('Зарегистрировано')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
