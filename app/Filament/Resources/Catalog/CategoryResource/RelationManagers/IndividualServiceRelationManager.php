<?php

namespace Tasawk\Filament\Resources\Catalog\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Models\IndividualService;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class IndividualServiceRelationManager extends RelationManager
{
    protected static string $relationship = 'individualServices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => (auth()->user()->roles->whereNotIn('name', ['Service Provider'])->count() > 0 ? $query : $query->whereHas('serviceProvider', fn ($query) => $query->where('id', auth()->user()->id))))
            ->heading(__('sections.individual_services'))
            ->recordTitleAttribute('id')
            ->heading(__('sections.hotel_services'))
            ->columns([
                TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('default')
                    ->label(__('forms.fields.images')),
                Tables\Columns\TextColumn::make('service_name')
                    ->url(fn ($record) => route('filament.admin.resources.catalog.individual-services.view', $record->id))
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serviceProvider.name')
                    ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                    ->label(__('forms.fields.serviceprovider')),
                Tables\Columns\TextColumn::make('price_night')
                    ->label(__('forms.fields.price_night')),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //                Tables\Actions\EditAction::make(),
                //                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
