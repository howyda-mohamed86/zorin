<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderResource\Pages;

use Tasawk\Filament\Resources\Providers\ServiceProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceProvider extends ViewRecord
{
    protected static string $resource = ServiceProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
