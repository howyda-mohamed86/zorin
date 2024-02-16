<?php

namespace Tasawk\Filament\Resources\Hotels\PublicUtilityResource\Pages;

use Tasawk\Filament\Resources\Hotels\PublicUtilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublicUtilities extends ListRecords
{
    protected static string $resource = PublicUtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
