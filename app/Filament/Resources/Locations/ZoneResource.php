<?php

namespace Tasawk\Filament\Resources\Locations;

use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Enum\ModelStatus;
use Tasawk\Filament\Resources\Locations;
use Tasawk\Models\Catalog\Option;
use Tasawk\Models\Zone;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZoneResource extends Resource {
    use Translatable;
    use HasTranslationLabel;

    protected static ?string $model = Zone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Section::make('basic_information')
                    ->schema([
                        TextInput::make('name')->required(),
                        Toggle::make('status')->default(1)
                            ->onColor('success')
                            ->offColor('danger')
                    ])

            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn(Zone $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->disabled(fn(Model $record): bool => !auth()->user()->can('update', $record))
                            ->requiresConfirmation()
                            ->action(fn(Zone $record) => $record->toggleStatus())

                    )

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ModelStatus::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Locations\ZoneResource\Pages\ListZones::route('/'),
            'create' => Locations\ZoneResource\Pages\CreateZone::route('/create'),
            'edit' => Locations\ZoneResource\Pages\EditZone::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return __('menu.settings');
    }
}
