<?php

namespace Tasawk\Filament\Resources\Hotels\HotelServicesResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHotelServices extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = HotelServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
