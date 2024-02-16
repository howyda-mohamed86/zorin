<?php

namespace Tasawk\Filament\Resources\NotificationResource\Pages;

use Tasawk\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords {


    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array {
        return [

        ];
    }
}
