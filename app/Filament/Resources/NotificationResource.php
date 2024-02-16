<?php

namespace Tasawk\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Filament\Resources\NotificationResource\Pages;
use Tasawk\Models\Notification;
use Tasawk\Traits\Filament\HasTranslationLabel;

class NotificationResource extends Resource implements HasShieldPermissions {

    use HasTranslationLabel;

    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form {
        return $form;
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('notifiable_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('forms.fields.text'))
                    ->searchable(true)
                    ->description(fn(Model $record): string => $record->body)


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn(Model $record) => $record->url),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
        ];
    }

    public static function getNavigationGroup(): ?string {
        return __('menu.notifications');
    }

    public static function getNavigationBadge(): ?string {
        return auth()->user()->unreadNotifications()->count();
    }

    public static function getPermissionPrefixes(): array {
        return [
            'view_any',
            'view',
            'delete',
            'delete_any',
        ];
    }
    public static function getNavigationLabel(): string {
        return __('menu.my_notifications');
    }
}
