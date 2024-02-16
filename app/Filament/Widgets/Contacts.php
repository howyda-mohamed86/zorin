<?php

namespace Tasawk\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Models\Content\Contact;

class Contacts extends BaseWidget {
    use HasWidgetShield;

    protected static ?int $sort = 11;

    public function table(Table $table): Table {
        return $table
            ->heading(__('sections.latest_contacts'))
            ->query(
                Contact::where('seen', 0)->orderBy('created_at', 'desc')->limit(10)
            )
            ->
            columns([
                TextColumn::make('id')->rowIndex()->toggleable(false),
                TextColumn::make('name')
                    ->formatStateUsing(fn(Model $record): string => $record->user->name ?? $record->name)
                    ->toggleable(false),
                TextColumn::make('email')
                    ->formatStateUsing(fn(Model $record): string => $record->user->email ?? $record->email)
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(false),

                TextColumn::make('phone')
                    ->formatStateUsing(fn(Model $record): string => $record->user->phone ?? $record->phone)
                    ->toggleable(false)
                    ->copyable()
                    ->copyMessage('Phone address copied')
                    ->copyMessageDuration(1500),


            ])->actions([
                Action::make('seen')
                    ->visible(fn(Model $record) => !$record->seen)
                    ->label(__('forms.fields.mark_as_seen'))
                    ->action(fn(Model $record) => $record->update(['seen' => 1])),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public function getTableHeading(): ?string {
        return __('sections.latest_contacts');
    }
}
