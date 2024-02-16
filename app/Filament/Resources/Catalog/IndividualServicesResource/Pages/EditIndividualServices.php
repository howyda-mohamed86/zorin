<?php

namespace Tasawk\Filament\Resources\Catalog\IndividualServicesResource\Pages;

use Tasawk\Filament\Resources\Catalog\IndividualServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndividualServices extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = IndividualServicesResource::class;

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
