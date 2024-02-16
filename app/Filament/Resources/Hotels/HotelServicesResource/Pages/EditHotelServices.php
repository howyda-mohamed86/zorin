<?php

namespace Tasawk\Filament\Resources\Hotels\HotelServicesResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotelServices extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = HotelServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
