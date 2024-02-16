<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderResource\Pages;

use Tasawk\Filament\Resources\Providers\ServiceProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceProvider extends EditRecord
{
    protected static string $resource = ServiceProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
