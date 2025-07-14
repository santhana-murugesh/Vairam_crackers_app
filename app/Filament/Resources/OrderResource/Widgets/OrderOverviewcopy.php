<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Order;

class OrderOverviewcopy extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders',Order::count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Incoming Orders', Order::whereIn('status', ['placed'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Confirm Orders',Order::whereIn('status', ['payment_received'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Cancelled Orders',Order::whereIn('status', ['cancelled'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

        ];
    }
}
