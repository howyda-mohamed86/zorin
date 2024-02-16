<?php

namespace Tasawk\Filament\Resources\Crm\CustomerResource\Pages;

use Tasawk\Filament\Resources\Crm\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
