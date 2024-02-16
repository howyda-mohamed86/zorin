<?php

namespace Tasawk\Filament\Resources\Catalog\IndividualServicesResource\Pages;

use Tasawk\Filament\Resources\Catalog\IndividualServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndividualServices extends ListRecords
{
    protected static string $resource = IndividualServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
