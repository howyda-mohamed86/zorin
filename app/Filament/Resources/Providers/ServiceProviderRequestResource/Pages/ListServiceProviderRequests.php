<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource\Pages;

use Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceProviderRequests extends ListRecords
{
    protected static string $resource = ServiceProviderRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
