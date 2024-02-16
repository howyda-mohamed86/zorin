<?php

namespace Tasawk\Filament\Resources\Providers;

use Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource\Pages;
use Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource\RelationManagers;
use Tasawk\Models\ServiceProviderRequest;
use Tasawk\Models\Package;
use Tasawk\Models\Zone;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;


class ServiceProviderRequestResource extends Resource
{
    use HasTranslationLabel;
    protected static ?string $model = ServiceProviderRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket-square';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->columnSpan(['xl' => 2])
                    ->required(),
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
                    ->columnSpan(['sm' => 2]),
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
                Select::make('package_id')
                    ->options(fn ($record, $get) => Package::where('status', 1)->pluck('name', 'id'))
                    ->required(),
                select::make('status')
                    ->options([
                        'pending' => __('forms.fields.pending'),
                        'approved' => __('forms.fields.approved'),
                        'rejected' => __('forms.fields.rejected'),
                    ])
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
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('iban'),
                Tables\Columns\TextColumn::make('national_id'),
                Tables\Columns\TextColumn::make('national_type_text')->label(__('forms.fields.national_type')),
                Tables\Columns\TextColumn::make('status_text')->label(__('forms.fields.status')),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListServiceProviderRequests::route('/'),
            // 'create' => Pages\CreateServiceProviderRequest::route('/create'),
            'view' => Pages\ViewServiceProviderRequest::route('/{record}'),
            'edit' => Pages\EditServiceProviderRequest::route('/{record}/edit'),
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
