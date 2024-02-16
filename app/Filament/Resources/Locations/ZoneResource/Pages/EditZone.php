<?php

namespace Tasawk\Filament\Resources\Locations\ZoneResource\Pages;

use Tasawk\Filament\Resources\Locations\ZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZone extends EditRecord {
    use EditRecord\Concerns\Translatable;

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
