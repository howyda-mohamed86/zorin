<?php

namespace Tasawk\Filament\Resources\Catalog;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Enum\ModelStatus;
use Tasawk\Filament\Resources\Catalog;
use Tasawk\Filament\Resources\Catalog\CategoryResource\RelationManagers\ProductRelationManager;
use Tasawk\Filament\Resources\Catalog\CategoryResource\RelationManagers\ChildrenRelationManager;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\Content\Page;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class CategoryResource extends Resource
{
    use Translatable;
    use HasTranslationLabel;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("basic_information")
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Toggle::make('status')
                            ->default(1)
                            ->onColor('success')
                            ->offColor('danger')
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (Category $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->disabled(fn (Model $record): bool => !auth()->user()->can('update', $record))
                            ->requiresConfirmation()
                            ->action(fn (Category $record) => $record->toggleStatus())

                    )
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ModelStatus::class)
            ])
            ->actions([
                //                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Catalog\CategoryResource\Pages\ListCategories::route('/'),
            'create' => Catalog\CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => Catalog\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.categories');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
