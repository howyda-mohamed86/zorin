<?php

namespace Tasawk\Filament\Resources\Hotels\HotelsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;


class HotelServices extends RelationManager
{
    protected static string $relationship = 'hotelServices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading(__('sections.hotel_services'))
            ->columns([
                TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('default')
                    ->label(__('forms.fields.images')),
                Tables\Columns\TextColumn::make('service_name')
                    ->url(fn ($record) => route('filament.admin.resources.hotels.hotel-services.view', $record->id))
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hotel.name')->label(__('forms.fields.hotel')),
                Tables\Columns\TextColumn::make('hotel.service_provider.name')
                    ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                    ->label(__('forms.fields.serviceprovider')),
                Tables\Columns\TextColumn::make('price_night')
                    ->label(__('forms.fields.price_night')),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
