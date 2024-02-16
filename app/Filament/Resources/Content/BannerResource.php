<?php

namespace Tasawk\Filament\Resources\Content;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Enum\ModelStatus;
use Tasawk\Filament\Resources\BannerResource\Pages;
use Tasawk\Filament\Resources\BannerResource\RelationManagers;
use Tasawk\Filament\Resources\Content;
use Tasawk\Models\Content\Banner;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerResource extends Resource {
    use HasTranslationLabel;

    protected static ?string $model = Banner::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form {

        return $form->schema([
            SpatieMediaLibraryFileUpload::make('image')
                ->columnSpan([
                    'xl' => 2,
                ])->required(),
            Toggle::make('status')->default(1)
                ->onColor('success')
                ->offColor('danger')
                ->translateLabel()

        ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('id'),
                SpatieMediaLibraryImageColumn::make('avatar')->translateLabel(),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        \Filament\Tables\Actions\Action::make('Active')
                            ->label(fn(Banner $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->disabled(fn(Model $record): bool => !auth()->user()->can('update', $record))
                            ->requiresConfirmation()
                            ->action(fn(Banner $record) => $record->toggleStatus())


                    )

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ModelStatus::class)
            ])
            ->actions([
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Content\BannerResource\Pages\ListBanners::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return __('menu.content');
    }
}
