<?php

namespace Tasawk\Filament\Resources\Content\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Tasawk\Filament\Resources\Content\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
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
