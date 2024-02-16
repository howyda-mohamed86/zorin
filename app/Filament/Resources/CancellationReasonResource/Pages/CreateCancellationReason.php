<?php

namespace Tasawk\Filament\Resources\CancellationReasonResource\Pages;

use Tasawk\Filament\Resources\CancellationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCancellationReason extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CancellationReasonResource::class;
    protected function getHeaderActions(): array {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
