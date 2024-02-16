<?php

namespace Tasawk\Filament\Resources\Catalog;

use Tasawk\Filament\Resources\Catalog\IndividualServicesResource\Pages;
use Tasawk\Filament\Resources\Catalog\IndividualServicesResource\RelationManagers;
use Tasawk\Models\IndividualService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Tasawk\Models\ServiceProvider;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\PublicUtility;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class IndividualServicesResource extends Resource
{
    use Translatable;
    use HasTranslationLabel;
    protected static ?string $model = IndividualService::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';

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
                Select::make('category_id')
                    ->options(fn ($record, $get) => Category::where('status', 1)->pluck('name', 'id'))
                    ->required(),
                Select::make('service_provider_id')
                    ->options(fn ($record, $get) => ServiceProvider::where('active', 1)->pluck('name', 'id'))
                    ->visible(fn ($record) => auth()->user()->roles->pluck('name')[0] != 'service_provider')
                    ->required(),
                TextInput::make('service_name')
                    ->required(),
                Select::make('public_utilities')
                    ->options(fn ($record, $get) => PublicUtility::where('status', 1)->pluck('name', 'id'))
                    ->multiple()
                    ->required(),
                Textarea::make('service_description')
                    ->label(__('forms.fields.description'))
                    ->required(),
                Textarea::make('address')
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
                Repeater::make("notes")
                    ->columnSpan(['xl' => 2])
                    ->label(__("forms.fields.notes_title"))
                    ->schema([
                        TextInput::make('note')
                            ->label(__("forms.fields.note_title"))
                            ->required(),
                    ]),
                Map::make("location")
                    ->columnSpan(['xl' => 2])
                    ->label(__("forms.fields.address"))
                    ->defaultLocation([24.7136, 46.6753])
                    ->defaultZoom(12)
                    ->draggable()
                    ->clickable()
                    ->geolocate(),
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
            ->modifyQueryUsing(fn (Builder $query) => auth()->user()->roles->pluck('name')[0] == 'service_provider' ? $query->where('service_provider_id', auth()->id()) : $query)
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('default')
                    ->label(__('forms.fields.images')),
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label(__('forms.fields.category')),
                Tables\Columns\TextColumn::make('serviceProvider.name')
                    ->visible(fn ($record) => auth()->user()->roles->pluck('name')[0] != 'service_provider')
                    ->label(__('forms.fields.serviceprovider')),
                Tables\Columns\TextColumn::make('price_night')
                    ->label(__('forms.fields.price_night')),
                IconColumn::make('status')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (IndividualService $record): string => $record->status ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->requiresConfirmation()
                            ->action(fn (IndividualService $record) => $record->toggleStatus())
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
                            TextEntry::make('service_name')->label(__('forms.fields.service_name')),
                            TextEntry::make('category.name')->label(__('forms.fields.category')),
                            TextEntry::make('serviceProvider.name')
                                ->visible(fn ($record) => auth()->user()->roles->pluck('name')[0] != 'service_provider')
                                ->label(__('forms.fields.serviceprovider')),
                            TextEntry::make('price_night')->label(__('forms.fields.price_night')),
                            TextEntry::make('area')->label(__('forms.fields.area')),
                            TextEntry::make('number_of_rooms')->label(__('forms.fields.number_of_rooms')),
                            TextEntry::make('number_of_beds')->label(__('forms.fields.number_of_beds')),
                            TextEntry::make('number_of_children')->label(__('forms.fields.number_of_children')),
                            TextEntry::make('number_of_adults')->label(__('forms.fields.number_of_adults')),
                            TextEntry::make('service_description')->label(__('forms.fields.description_service')),
                            TextEntry::make('address')
                                ->url(fn ($record) => "https://maps.google.com/?q=" . $record->location['lat'] . "," . $record->location['lng'], '_blank')
                                ->color('primary')
                                ->label(__('forms.fields.address')),
                            TextEntry::make('public_utilities_text')->label(__('forms.fields.is_public_utility')),
                            TextEntry::make('notes_text')->label(__('forms.fields.notes')),
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
            'index' => Pages\ListIndividualServices::route('/'),
            'create' => Pages\CreateIndividualServices::route('/create'),
            'view' => Pages\ViewIndividualServices::route('/{record}'),
            'edit' => Pages\EditIndividualServices::route('/{record}/edit'),
        ];
    }
    public static function getNavigationGroup(): ?string
    {
        return __('menu.categories');
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->roles->pluck('name')[0] != 'service_provider') {
            return IndividualService::where('status', 1)->count();
        } else {
            return IndividualService::where('status', 1)->where('service_provider_id', auth()->id())->count();
        }
    }
}
