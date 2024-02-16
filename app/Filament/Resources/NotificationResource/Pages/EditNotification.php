<?php

namespace Tasawk\Filament\Resources\NotificationResource\Pages;

use Tasawk\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotification extends EditRecord {
    protected static string $resource = NotificationResource::class;
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
