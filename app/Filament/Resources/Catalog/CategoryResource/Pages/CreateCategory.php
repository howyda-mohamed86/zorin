<?php

namespace Tasawk\Filament\Resources\Catalog\CategoryResource\Pages;

use Tasawk\Filament\Resources\Catalog\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord {
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
