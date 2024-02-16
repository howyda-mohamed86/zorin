<?php

namespace Tasawk\Filament\Resources\Employees\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Tasawk\Filament\Resources\Employees\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
