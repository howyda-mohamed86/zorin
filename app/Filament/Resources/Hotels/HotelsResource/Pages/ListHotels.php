<?php

namespace Tasawk\Filament\Resources\Hotels\HotelsResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHotels extends ListRecords
{
    protected static string $resource = HotelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
