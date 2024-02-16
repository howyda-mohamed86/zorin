<?php

namespace Tasawk\Filament\Resources\Hotels\HotelServicesResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHotelServices extends ViewRecord
{
    protected static string $resource = HotelServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
