<?php

namespace Tasawk\Filament\Resources\Hotels;

use Tasawk\Filament\Resources\Hotels\HotelServicesResource\Pages;
use Tasawk\Filament\Resources\Hotels\HotelServicesResource\RelationManagers;
use Tasawk\Models\HotelService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Resources\Concerns\Translatable;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Tasawk\Models\ServiceProvider;
use Tasawk\Models\PublicUtility;
use Tasawk\Models\Hotel;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class HotelServicesResource extends Resource implements HasShieldPermissions
{
    use HasTranslationLabel;
    use Translatable;
    protected static ?string $model = HotelService::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make("default")
                    ->label(__('forms.fields.images'))
                    ->columnSpan(['xl' => 2])
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->columnSpan(['xl' => 2])
                            ->image()
                            ->collection('default')
                            ->required(),
                    ])->defaultItems(1),
                Select::make('hotel_id')
                    ->options(fn ($record, $get) => Hotel::where('status', 1)
                        ->where(function ($query) use ($get) {
                            if (auth()->user()->roles->whereIn('name', ['Service Provider'])->count() > 0) {
                                $query->where('service_provider_id', auth()->id());
                            }
                        })->pluck('name', 'id'))
                    ->live()
                    ->required(),
                TextInput::make('service_name')
                    ->required(),
                Textarea::make('description_service')
                    ->required(),
                Textarea::make('location')
                    ->required(),
                TextInput::make('price_night')
                    ->required(),
                TextInput::make('area')
                    ->required(),
                TextInput::make('number_of_rooms')
                    ->integer()
                    ->required(),
                TextInput::make('number_of_beds')
                    ->integer()
                    ->required(),
                TextInput::make('number_of_children')
                    ->integer()
                    ->required(),
                TextInput::make('number_of_adults')
                    ->integer()
                    ->required(),
                Select::make('public_utilities')
                    ->options(fn ($record, $get) => PublicUtility::where('status', 1)->pluck('name', 'id'))
                    ->multiple()
                    ->columnSpan(['xl' => 2])
                    ->required(),
                Toggle::make('status')
                    ->default(1)
                    ->required(),
                Hidden::make('created_by')
                    ->default(auth()->id())
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => (auth()->user()->roles->whereNotIn('name', ['Service Provider'])->count() > 0 ? $query : $query->whereHas('hotel', function ($query) {
            $query->where('service_provider_id', auth()->id());
        })))
        ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('default')
                    ->label(__('forms.fields.images')),
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hotel.name')->label(__('forms.fields.hotel')),
                Tables\Columns\TextColumn::make('hotel.service_provider.name')
                    ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                    ->label(__('forms.fields.serviceprovider')),
                Tables\Columns\TextColumn::make('price_night')
                    ->label(__('forms.fields.price_night')),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (HotelService $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->requiresConfirmation()
                            ->action(fn (HotelService $record) => $record->toggleStatus())
                    )

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    static public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Grid::make()->schema([
                    \Filament\Infolists\Components\Section::make("basic_information")
                        ->schema([
                            //images
                            TextEntry::make('service_name')->label(__('forms.fields.service_name')),
                            TextEntry::make('hotel.name')->label(__('forms.fields.hotel')),
                            TextEntry::make('hotel.service_provider.name')
                                ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                                ->label(__('forms.fields.serviceprovider')),
                            TextEntry::make('price_night')->label(__('forms.fields.price_night')),
                            TextEntry::make('description_service')->label(__('forms.fields.description_service')),
                            TextEntry::make('location')->label(__('forms.fields.location')),
                            TextEntry::make('area')->label(__('forms.fields.area')),
                            TextEntry::make('number_of_rooms')->label(__('forms.fields.number_of_rooms')),
                            TextEntry::make('number_of_beds')->label(__('forms.fields.number_of_beds')),
                            TextEntry::make('number_of_children')->label(__('forms.fields.number_of_children')),
                            TextEntry::make('number_of_adults')->label(__('forms.fields.number_of_adults')),
                            TextEntry::make('public_utilities_text')->label(__('forms.fields.is_public_utility')),
                            TextEntry::make('status_text')->label(__('forms.fields.status')),
                        ])->columns(3),
                ])->columns(2),
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
            'index' => Pages\ListHotelServices::route('/'),
            'create' => Pages\CreateHotelServices::route('/create'),
            'view' => Pages\ViewHotelServices::route('/{record}'),
            'edit' => Pages\EditHotelServices::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.hotels');
    }

    public static function getNavigationBadge(): ?string
    {
        if ((auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0) {
            return HotelService::where('status', 1)->count();
        } else {
            return HotelService::where('status', 1)->whereHas('hotel', function ($query) {
                $query->where('service_provider_id', auth()->id());
            })->count();
        }
    }
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
