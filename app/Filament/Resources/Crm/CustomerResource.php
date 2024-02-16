<?php

namespace Tasawk\Filament\Resources\Crm;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationGroup;
use Tasawk\Enum\OrderStatus;
use Tasawk\Enum\ReceiptMethods;
use Tasawk\Filament\Resources\Crm;
use Tasawk\Filament\Resources\Crm\CustomerResource\RelationManagers\AddressBookRelationManager;
use Tasawk\Filament\Resources\Crm\CustomerResource\RelationManagers\OrdersRelationManager;
use Tasawk\Models\Customer;
use Tasawk\Models\Order;
use Tasawk\Models\Zone;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class CustomerResource extends Resource
{
    use HasTranslationLabel;

    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->columnSpan(['xl' => 2]),
                TextInput::make('name')
                    ->label(__('forms.fields.first_name'))
                    ->required(),
                TextInput::make('last_name')
                    ->label(__('forms.fields.last_name'))
                    ->required(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->autocomplete("off")
                    ->unique(ignoreRecord: true),
                TextInput::make('balance')
                    ->columnSpan(1)
                    ->integer()
                    ->required()
                    ->label(__('forms.fields.balance')),
                TextInput::make('phone')
                    ->prefix("+966")
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->autocomplete("off")
                    ->columnSpan(['sm' => 2]),
                Select::make('gender')
                    ->options([
                        'male' => __('forms.fields.male'),
                        'female' => __('forms.fields.female'),
                    ])
                    ->required()
                    ->columnSpan(1),
                DatePicker::make('birth_date')
                    ->columnSpan(1)
                    ->required()
                    ->label(__('forms.fields.birth_date')),

                Toggle::make('active')->default(1)
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->translateLabel()
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('avatar'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Phone address copied')
                    ->copyMessageDuration(1500),
                IconColumn::make('active')
                    ->boolean()
                    ->action(
                        Action::make('Active')
                            ->label(fn (Model $record): string => $record->active ? __('panel.messages.deactivate') : __('panel.messages.activate'))
                            ->disabled(fn (Model $record): bool => !auth()->user()->can('update', static::getModel()))
                            ->requiresConfirmation()
                            ->disabled(fn (Model $record): bool => !auth()->user()->can('update', static::getModel()))
                            ->action(fn (Model $record) => $record->toggleActive())


                    )
            ])
            ->filters([
                Filter::make('ID')
                    ->form([
                        TextInput::make('id'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['id'], fn (Builder $query, $date): Builder => $query->where('id', $data['id']));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['id']) {
                            return null;
                        }

                        return __('forms.fields.id') . " " . $data['id'];
                    }),
                Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('active', 1))
                    ->toggle(),
            ])
            ->actions([


                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Customer $customer) {
                        if ($customer->orders()->count()) {
                            Notification::make()
                                ->warning()
                                ->title(__('panel.messages.warning'))
                                ->body(__('panel.messages.customer_has_many_order', ['customer' => $customer->name]))
                                ->persistent()
                                ->send();
                            $action->cancel();
                        }
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->checkIfRecordIsSelectableUsing(fn (Model $record): bool => !$record->orders()->count())
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->striped();
    }

    static public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()->schema([
                    Section::make("basic_information")
                        ->schema([
                            TextEntry::make('id'),
                            TextEntry::make('name'),
                            TextEntry::make('email'),
                            TextEntry::make('phone'),
                            TextEntry::make('active')->badge(),
                        ])->columns(1),

                ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make(__('sections.orders'), [
                OrdersRelationManager::class,
            ]),
            RelationGroup::make(__('sections.address_book'), [
                AddressBookRelationManager::class
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Crm\CustomerResource\Pages\ListCustomers::route('/'),
            'view' => Crm\CustomerResource\Pages\ViewCustomers::route('/{record}/view'),
        ];
    }

    //    public static function getNavigationGroup(): ?string {
    //        return __('menu.crm');
    //    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    // public static function getNavigationGroup(): ?string {
    //     return __('menu.crm');
    // }
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }
    //    public static function getGlobalSearchResultDetails(Model $record): array
    //    {
    //        return [
    //            __('forms.fields.name') => $record->name,
    //            __('forms.fields.email') => $record->email,
    //            __('forms.fields.phone') => $record->phone,
    //        ];
    //    }
}
