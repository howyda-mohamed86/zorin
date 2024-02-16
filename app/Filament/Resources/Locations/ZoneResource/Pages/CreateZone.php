<?php

namespace Tasawk\Filament\Resources\Locations\ZoneResource\Pages;

use Tasawk\Filament\Resources\Locations\ZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZone extends CreateRecord {
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
