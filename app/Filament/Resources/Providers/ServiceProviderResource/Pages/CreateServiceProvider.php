<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderResource\Pages;

use Tasawk\Filament\Resources\Providers\ServiceProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceProvider extends CreateRecord
{
    protected static string $resource = ServiceProviderResource::class;
}
