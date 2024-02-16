<?php

namespace Tasawk\Filament\Resources\Providers\PackageResource\Pages;

use Tasawk\Filament\Resources\Providers\PackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackages extends ListRecords
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
