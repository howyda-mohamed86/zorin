<?php

namespace Tasawk\Filament\Resources\CancellationReasonResource\Pages;

use Tasawk\Filament\Resources\CancellationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCancellationReason extends ViewRecord
{
    protected static string $resource = CancellationReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
