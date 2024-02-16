<?php

namespace Tasawk\Filament\Resources\Hotels\HotelsResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHotels extends ViewRecord
{
    protected static string $resource = HotelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
