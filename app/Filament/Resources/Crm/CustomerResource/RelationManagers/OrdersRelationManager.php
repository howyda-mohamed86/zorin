<?php

namespace Tasawk\Filament\Resources\Crm\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Str;
use Tasawk\Enum\OrderStatus;
use Tasawk\Models\Order;

class OrdersRelationManager extends RelationManager {
    protected static string $relationship = 'Orders';

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('id')
            ->heading(__('sections.orders'))
            ->columns([

                TextColumn::make('order_number')->searchable(),
                TextColumn::make('branch.name')->searchable(),
                TextColumn::make('address.name')->searchable(),
                TextColumn::make('date')->searchable(),
                TextColumn::make('receipt_method')
                    ->badge()
                    ->color(fn($state) => $state->getColor())
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => $state->getColor())
                    ->formatStateUsing(function ($record) {
                        return $record->status == OrderStatus::CANCELED ? $record->status->getLabel() . ' - ' . $record->cancellation?->getReason() : $record->status->getLabel();
                    })
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn($state) => $state->getColor())
                    ->searchable(),
                TextColumn::make('total')
                    ->money()
                    ->searchable(),
                TextColumn::make('total')
                    ->formatStateUsing(fn($record) => $record->total->foramt())
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('SAR'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('change_status')
                    ->label(__('panel.actions.change_status'))
                    ->icon('heroicon-o-bolt')
                    ->disabled(fn(Order $record) => !$record->getAvailableStatus()->count())
                    ->form([
                        Select::make('status')
                            ->options(fn($record) => $record->getAvailableStatus()->pluck('label', 'value')->toArray())
                            ->required(),
                    ])
                    ->visible(fn(Order $record) => auth()->user()->can('update', $record))
                    ->action(function (array $data, Order $record): void {
                        if (Str::of($data['status'])->contains("canceled-")) {
                            $record->cancel(Str::of($data['status'])->after("-"));
                            return;
                        }
                        $record->update(['status' => $data['status']]);
                    }),
            ]);
    }
}
