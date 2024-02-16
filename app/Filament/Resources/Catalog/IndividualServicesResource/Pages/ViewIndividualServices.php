<?php

namespace Tasawk\Filament\Resources\Catalog\IndividualServicesResource\Pages;

use Tasawk\Filament\Resources\Catalog\IndividualServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIndividualServices extends ViewRecord
{
    protected static string $resource = IndividualServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
