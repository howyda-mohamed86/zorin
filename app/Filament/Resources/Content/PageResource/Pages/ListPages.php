<?php

namespace Tasawk\Filament\Resources\Content\PageResource\Pages;

use Tasawk\Filament\Resources\Content\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords {

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
//            Actions\LocaleSwitcher::make(),
        ];
    }
}
