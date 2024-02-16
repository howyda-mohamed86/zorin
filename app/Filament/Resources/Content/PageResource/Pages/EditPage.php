<?php

namespace Tasawk\Filament\Resources\Content\PageResource\Pages;

use Tasawk\Filament\Resources\Content\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
