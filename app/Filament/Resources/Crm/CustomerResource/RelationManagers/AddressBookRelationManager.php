<?php

namespace Tasawk\Filament\Resources\Crm\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class AddressBookRelationManager extends RelationManager {
    protected static string $relationship = 'addresses';

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
            ->heading(__('sections.address_books'))
            ->columns([
                TextColumn::make('id')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('zone.name')->searchable(),
                TextColumn::make('state')->searchable(),
                TextColumn::make('street')->searchable(),
                TextColumn::make('building_number')->searchable(),
                TextColumn::make('floor')->searchable(),
                IconColumn::make('primary')->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



}
