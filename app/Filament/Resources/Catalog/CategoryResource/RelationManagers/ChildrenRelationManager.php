<?php

namespace Tasawk\Filament\Resources\Catalog\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChildrenRelationManager extends RelationManager {
    protected static string $relationship = 'children';

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label("ID"),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([

            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ]);
    }
}
