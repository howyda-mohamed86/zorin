<?php

namespace Tasawk\Filament\Resources\Content\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Tasawk\Filament\Resources\Content\PostResource;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;
    use EditRecord\Concerns\Translatable;

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
