<?php

namespace Tasawk\Filament\Resources\CancellationReasonResource\Pages;

use Tasawk\Filament\Resources\CancellationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCancellationReason extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CancellationReasonResource::class;

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
