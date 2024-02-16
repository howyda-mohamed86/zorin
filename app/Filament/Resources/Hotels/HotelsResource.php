<?php

namespace Tasawk\Filament\Resources\Hotels;

use Tasawk\Filament\Resources\Hotels\HotelsResource\Pages;
use Tasawk\Filament\Resources\HotelsResource\RelationManagers;
use Tasawk\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Tasawk\Models\ServiceProvider;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Tasawk\Filament\Resources\Hotels\HotelsResource\RelationManagers\HotelServices;

class HotelsResource extends Resource implements HasShieldPermissions
{
    use HasTranslationLabel;
    use Translatable;

    protected static ?string $model = Hotel::class;
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('image')
                    ->columnSpan(['xl' => 2])
                    ->collection('default')
                    ->required(),
                TextInput::make('name')
                    ->columnSpan(2)
                    ->required(),
                Select::make('service_provider_id')
                    ->columnSpan(2)
                    ->live()
                    ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                    ->options(fn ($record, $get) => ServiceProvider::where('active', 1)->pluck('name', 'id'))
                    ->required(),
                TextInput::make('address')
                    ->columnSpan(['xl' => 2])
                    ->required(),
                Map::make("location")
                    ->columnSpan(['xl' => 2])
                    ->label(__("forms.fields.address"))
                    ->defaultLocation([24.7136, 46.6753])
                    ->defaultZoom(12)
                    ->draggable()
                    ->clickable()
                    ->geolocate(),
                Repeater::make("notes")
                    ->columnSpan(['xl' => 2])
                    ->label(__("forms.fields.notes_title"))
                    ->schema([
                        TextInput::make('note')
                            ->label(__("forms.fields.note_title"))
                            ->required(),
                    ])->defaultItems(1),
                Toggle::make('status')
                    ->columnSpan(['xl' => 2])
                    ->default(true),
                Hidden::make('created_by')
                    ->default(auth()->id())
                    ->columnSpan(['xl' => 2]),
                Hidden::make('updated_by')
                    ->default(auth()->id())
                    ->columnSpan(['xl' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => (auth()->user()->roles->whereNotIn('name', ['Service Provider'])->count() > 0 ? $query : $query->where('service_provider_id', auth()->id())))
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label(__('forms.fields.image')),
                Tables\Columns\TextColumn::make('name')->searchable()->label(__('forms.fields.name')),
                Tables\Columns\TextColumn::make('service_provider.name')
                    ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                    ->label(__('forms.fields.service_provider_id')),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('forms.fields.address')),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (Hotel $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->requiresConfirmation()
                            ->action(fn (Hotel $record) => $record->toggleStatus())
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
                            TextEntry::make('name')->label(__('forms.fields.hotel')),
                            TextEntry::make('service_provider.name')
                                ->visible(fn ($record) => (auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0)
                                ->label(__('forms.fields.serviceprovider')),
                            TextEntry::make('address')
                                ->url(fn ($record) => "https://maps.google.com/?q=" . $record->location['lat'] . "," . $record->location['lng'], '_blank')
                                ->color('primary')
                                ->label(__('forms.fields.address')),
                            TextEntry::make('notes_text')->label(__('forms.fields.notes')),
                            TextEntry::make('status_text')->label(__('forms.fields.status')),
                        ])->columns(3),
                ])->columns(2),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            'hotelServices' => HotelServices::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotels::route('/create'),
            'edit' => Pages\EditHotels::route('/{record}/edit'),
            'view' => Pages\ViewHotels::route('/{record}'),
        ];
    }
    public static function getNavigationGroup(): ?string
    {
        return __('menu.hotels');
    }
    public static function getNavigationBadge(): ?string
    {
        if ((auth()->user()->roles->whereNotIn('name', ['Service Provider']))->count() > 0) {
            return Hotel::where('status', 1)->count();
        } else {
            return Hotel::where('status', 1)->where('service_provider_id', auth()->id())->count();
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
