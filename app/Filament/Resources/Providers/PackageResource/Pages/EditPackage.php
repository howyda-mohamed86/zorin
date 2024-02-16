<?php

namespace Tasawk\Filament\Resources\Providers\PackageResource\Pages;

use Tasawk\Filament\Resources\Providers\PackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackage extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
