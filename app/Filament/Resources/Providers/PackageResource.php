<?php

namespace Tasawk\Filament\Resources\Providers;

use Tasawk\Filament\Resources\Providers\PackageResource\Pages;
use Tasawk\Filament\Resources\PackageResource\RelationManagers;
use Tasawk\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PackageResource extends Resource
{
    use HasTranslationLabel;
    use Translatable;
    protected static ?string $model = Package::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('forms.fields.name'))
                    ->required(),
                Forms\Components\TextInput::make('percentage')
                    ->label(__('forms.fields.percentage'))
                    ->visible(fn ($record) => $record->mapper_id == 2)
                    ->required(),
                Forms\Components\TextInput::make('number_of_ads')
                    ->label(__('forms.fields.number_of_ads'))
                    ->visible(fn ($record) => $record->mapper_id == 1)
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label(__('forms.fields.price'))
                    ->visible(fn ($record) => $record->mapper_id == 1)
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->label(__('forms.fields.duration'))
                    ->visible(fn ($record) => $record->mapper_id == 1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                Tables\Columns\TextColumn::make('name'),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (Package $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->requiresConfirmation()
                            ->action(fn (Package $record) => $record->toggleStatus())
                    )

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.service_providers');
    }
    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }
}
