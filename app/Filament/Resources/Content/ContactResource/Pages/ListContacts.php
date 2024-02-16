<?php

namespace Tasawk\Filament\Resources\Content\ContactResource\Pages;

use Tasawk\Filament\Resources\Content\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
