<?php

namespace Tasawk\Filament\Resources\Providers;

use Tasawk\Filament\Resources\Providers\ServiceProviderResource\Pages;
use Tasawk\Filament\Resources\Providers\ServiceProviderResource\RelationManagers;
use Tasawk\Models\ServiceProvider;
use Tasawk\Models\Zone;
use Tasawk\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class ServiceProviderResource extends Resource
{
    use HasTranslationLabel;
    protected static ?string $model = ServiceProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->columnSpan(['xl' => 2]),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->autocomplete("off")
                    ->columnSpan(['sm' => 2, 'xl' => 1])
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->prefix("+966")
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->autocomplete("off")
                    ->columnSpan(['sm' => 1]),
                Select::make('package_id')
                    ->columnSpan(['sm' => 1])
                    ->options(fn ($record, $get) => Package::where('status', 1)->pluck('name', 'id'))
                    ->required(),
                TextInput::make('iban')
                    ->required(),
                TextInput::make('national_id')
                    ->required(),
                Select::make('national_type')
                    ->options([
                        'citizen' => __('forms.fields.citizen'),
                        'resident' => __('forms.fields.resident'),
                    ])
                    ->required(),
                Select::make('zone_id')
                    ->options(fn ($record, $get) => Zone::where('status', 1)->pluck('name', 'id'))
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->confirmed()
                    ->autocomplete("new-password"),
                TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->autocomplete("off"),
                SpatieMediaLibraryFileUpload::make('commercial_register')
                    ->columnSpan(['xl' => 2])
                    ->collection('commercial_register')
                    ->required(),
                Toggle::make('active')
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex(),
                SpatieMediaLibraryImageColumn::make('avatar'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('iban'),
                Tables\Columns\TextColumn::make('national_id'),
                Tables\Columns\TextColumn::make('national_type_text')->label(__('forms.fields.national_type')),
                IconColumn::make('active')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (ServiceProvider $record): string => $record->active ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            // ->disabled(fn(ServiceProvider $record): bool => !auth()->user()->can('update', static::getModel()))
                            ->requiresConfirmation()
                            // ->disabled(fn(ServiceProvider $record): bool => !auth()->user()->can('update', static::getModel()))
                            ->action(fn (ServiceProvider $record) => $record->toggleActive())


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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceProviders::route('/'),
            'create' => Pages\CreateServiceProvider::route('/create'),
            'view' => Pages\ViewServiceProvider::route('/{record}'),
            'edit' => Pages\EditServiceProvider::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.service_providers');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
