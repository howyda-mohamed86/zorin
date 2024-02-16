<?php

namespace Tasawk\Filament\Resources\Locations\ZoneResource\Pages;

use Tasawk\Filament\Resources\Locations\ZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZones extends ListRecords
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
