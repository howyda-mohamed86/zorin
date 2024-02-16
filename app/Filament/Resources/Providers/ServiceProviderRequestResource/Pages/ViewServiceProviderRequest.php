<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource\Pages;

use Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceProviderRequest extends ViewRecord
{
    protected static string $resource = ServiceProviderRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
