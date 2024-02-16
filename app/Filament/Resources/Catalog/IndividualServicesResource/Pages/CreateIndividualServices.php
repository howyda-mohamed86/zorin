<?php

namespace Tasawk\Filament\Resources\Catalog\IndividualServicesResource\Pages;

use Tasawk\Filament\Resources\Catalog\IndividualServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIndividualServices extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = IndividualServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['service_provider_id'])) {
            $data['service_provider_id'] = auth()->user()->id;
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
