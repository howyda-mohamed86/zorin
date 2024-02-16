<?php

namespace Tasawk\Filament\Resources\Hotels;

use Tasawk\Filament\Resources\Hotels\PublicUtilityResource\Pages;
use Tasawk\Filament\Resources\Hotels\PublicUtilityResource\RelationManagers;
use Tasawk\Models\PublicUtility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Resources\Concerns\Translatable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class PublicUtilityResource extends Resource
{
    use HasTranslationLabel;
    use Translatable;
    protected static ?string $model = PublicUtility::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpan(['xl' => 2])
                    ->required(),
                Toggle::make('status')
                    ->default(1)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (PublicUtility $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->requiresConfirmation()
                            ->action(fn (PublicUtility $record) => $record->toggleStatus())
                    )
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicUtilities::route('/'),
            'create' => Pages\CreatePublicUtility::route('/create'),
            'edit' => Pages\EditPublicUtility::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.hotels');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
