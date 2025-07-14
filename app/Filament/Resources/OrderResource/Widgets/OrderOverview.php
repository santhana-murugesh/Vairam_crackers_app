<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Order;

class OrderOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Total Orders',Order::count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Confirm Orders',Order::whereIn('status', ['payment_received'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Dispatched Orders',Order::whereIn('status', ['dispatched'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('packing Orders',Order::whereIn('status', ['packing'])->count())
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),

            Stat::make('Total Sales Amount', function () {
                $totalSales = Order::sum('sub_total');
                $total= $totalSales;
                return '₹ ' . number_format($total, 0);
            })
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),
        ];
    }
}
