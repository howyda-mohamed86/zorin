<?php

namespace Tasawk\Filament\Resources\Hotels\HotelsResource\Pages;

use Tasawk\Filament\Resources\Hotels\HotelsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHotels extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = HotelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function afterCreate(){
        $record = $this->record;
        if(auth()->user()->roles->whereIn('name', ['Service Provider'])->count() > 0){
            $record->service_provider_id = auth()->user()->id;
            $record->save();
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
