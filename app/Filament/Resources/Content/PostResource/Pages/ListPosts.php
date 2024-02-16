<?php

namespace Tasawk\Filament\Resources\Content\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Tasawk\Filament\Resources\Content\PostResource;

class ListPosts extends ListRecords {

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
//            Actions\LocaleSwitcher::make(),
        ];
    }
}
