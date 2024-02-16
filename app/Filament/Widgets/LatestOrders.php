<?php

namespace Tasawk\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Tasawk\Enum\OrderStatus;
use Tasawk\Models\Order;

class LatestOrders extends BaseWidget {
    use HasWidgetShield;

    protected static ?int $sort = 6;
    public function table(Table $table): Table {
        return $table
            ->heading(__('sections.latest_orders'))
            ->description(__('sections.latest_orders_description'))
            ->query(
                Order::query()
                    ->latest()
                    ->where('status', OrderStatus::PENDING)
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('index')->rowIndex()->toggleable(false),

                Tables\Columns\TextColumn::make('customer.name')
                    ->toggleable(false),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => $state->getColor())
                    ->formatStateUsing(function ($record) {
                        return $record->status->getLabel();
                    })
                    ->toggleable(false),
                Tables\Columns\TextColumn::make('total')
                    ->toggleable(false),
                Tables\Columns\TextColumn::make('date')
                    ->since()
                    ->toggleable(false),
            ]);
    }
    public function getTableHeading(): ?string {
        return __('sections.latest_orders');
    }

}
