<?php

namespace Tasawk\Filament\Resources\Content\PageResource\Pages;

use Tasawk\Filament\Resources\Content\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
    use CreateRecord\Concerns\Translatable;

    protected function getHeaderActions(): array {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
