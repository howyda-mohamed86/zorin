<?php

namespace Tasawk\Filament\Resources\Content\BannerResource\Pages;

use Tasawk\Filament\Resources\Content\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
