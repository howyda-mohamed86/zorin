<?php

namespace Tasawk\Filament\Resources\Hotels\HotelsResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotels extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = HotelsResource::class;

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
