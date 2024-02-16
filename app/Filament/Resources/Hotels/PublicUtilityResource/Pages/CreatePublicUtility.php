<?php

namespace Tasawk\Filament\Resources\Hotels\PublicUtilityResource\Pages;

use Tasawk\Filament\Resources\Hotels\PublicUtilityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePublicUtility extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = PublicUtilityResource::class;

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
