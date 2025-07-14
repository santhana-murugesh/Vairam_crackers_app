<?php

namespace App\Filament\Resources\AllOrdersResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Order;

class AllOrdersOverview extends BaseWidget
{
    protected function getCards(): array
    {
            return [
                Card::make('Total Orders',Order::count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

                Card::make('Confirmed Orders',Order::whereIn('status', ['payment_received'])->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

                Card::make('Packing Orders',Order::whereIn('status', ['packing'])->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

                Card::make('Cancel Order',Order::whereIn('status', ['cancelled'])->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            ];
    }
}
