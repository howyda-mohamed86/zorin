<?php

namespace Tasawk\Filament\Resources\Content;


use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Tasawk\Filament\Resources\Content;
use Tasawk\Models\ContactType;
use Tasawk\Models\Content\Contact;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ContactResource extends Resource implements HasShieldPermissions{
    use HasTranslationLabel;

    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form {
        return $form
            ->schema([


                Textarea::make('message')
                    ->rows(10),


                Select::make("user_id")
                    ->relationship("user", 'name'),

                TextInput::make('name')
                    ->formatStateUsing(fn(Model $record): string => $record->user->name ?? $record->name)

                    ->required(),

                TextInput::make('email')
                    ->formatStateUsing(fn(Model $record): string => $record->user->email ?? $record->email)
                    ->required()
                    ->email()
                    ->autocomplete("off")
                    ->unique(ignoreRecord: true),

                TextInput::make('phone')
                    ->formatStateUsing(fn(Model $record): string => $record->user->phone ?? $record->phone)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->autocomplete("off"),

                Toggle::make('seen')->default(1)
                    ->onColor('success')
                    ->offColor('danger')
            ])->columns(1);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('id')->searchable(),
                TextColumn::make('name')
                    ->default(fn( $record): string => $record->user->name ?? $record->name)
                    ->searchable(),
                TextColumn::make('email')
                    ->default(fn(Model $record): string => $record->user->email ?? $record->email)
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                TextColumn::make('phone')
                    ->default(fn(Model $record): string => $record->user->phone ?? $record->phone)
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Phone address copied')
                    ->copyMessageDuration(1500),



                TextColumn::make('message')
                    ->label(__("forms.fields.message_body"))
                    ->limit(50)
                    ->searchable(),
                IconColumn::make('seen')
                    ->boolean(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('seen')
                    ->visible(fn(Model $record) => !$record->seen)
                    ->label(__('forms.fields.mark_as_seen'))
                    ->hidden(fn(Model $record): bool => !auth()->user()->can('update', $record))
                    ->action(fn(Model $record) => $record->update(['seen' => 1])),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exports([
                        ExcelExport::make("CSV")
                            ->fromTable()
                            ->withFilename(fn () => static::getPluralLabel().'-'.now()->format('Y-m-d'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),



                    ]),


                    Tables\Actions\DeleteBulkAction::make(),


                ]),

            ])
            ->emptyStateActions([

            ]);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Content\ContactResource\Pages\ListContacts::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return __('menu.content');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('seen',0)->count();
    }
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'view',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
