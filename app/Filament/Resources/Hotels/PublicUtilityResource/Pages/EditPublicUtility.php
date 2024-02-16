<?php

namespace Tasawk\Filament\Resources\Hotels\PublicUtilityResource\Pages;

use Tasawk\Filament\Resources\Hotels\PublicUtilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicUtility extends EditRecord
{

    use EditRecord\Concerns\Translatable;
    protected static string $resource = PublicUtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
