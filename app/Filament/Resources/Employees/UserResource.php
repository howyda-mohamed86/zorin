<?php

namespace Tasawk\Filament\Resources\Employees;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Enum\ModelStatus;
use Tasawk\Models\User;
use Tasawk\Traits\Filament\HasTranslationLabel;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class UserResource extends Resource
{
    use HasTranslationLabel;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {

        return $form->schema(
            [
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->columnSpan(['xl' => 2])
                    ->nullable(),

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

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->confirmed()
                    ->autocomplete("new-password"),

                TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->autocomplete("off"),

                Select::make("role")
                    ->relationship("roles", "name", fn (Builder $query) => $query->whereNotIn('name', ['customer', 'manager', 'operator', 'panel_user', 'super_admin']))
                    ->required()
                    ->columnSpan(2)
                    ->preload(),

                Toggle::make('active')->default(1)
                    ->onColor('success')
                    ->offColor('danger')
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->whereNotIn('name', ['customer', 'manager', 'operator', 'panel_user', 'super_admin', 'Service Provider'])))
            ->columns([
                TextColumn::make('id')->searchable(),
                SpatieMediaLibraryImageColumn::make('avatar'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('forms.fields.role')
                    ->default("N/A")
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
                SelectFilter::make('active')
                    ->options(ModelStatus::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => UserResource\Pages\ListUsers::route('/'),
        ];
    }
    public static function getNavigationGroup(): ?string
    {
        return __('menu.employees');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereHas('roles', fn ($q) => $q->whereNotIn('name', ['customer', 'manager', 'operator', 'panel_user', 'super_admin', 'Service Provider']))->count();
    }
}
