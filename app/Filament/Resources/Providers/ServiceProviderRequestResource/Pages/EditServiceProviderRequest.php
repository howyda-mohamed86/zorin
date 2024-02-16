<?php

namespace Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource\Pages;

use AnourValar\EloquentSerialize\Service;
use Tasawk\Filament\Resources\Providers\ServiceProviderRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Tasawk\Models\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EditServiceProviderRequest extends EditRecord
{
    protected static string $resource = ServiceProviderRequestResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
    }
    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate($record, $data): Model
    {
        if ($data['status'] == 'approved' && $record->status == 'pending') {
            $service_provider = ServiceProvider::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $record->password,
                'iban' => $data['iban'],
                'national_id' => $data['national_id'],
                'national_type' => $data['national_type'],
                'package_id' => $data['package_id'],
                'active' => 1,
            ]);
            $media = Media::where('model_id', $record->id)
                ->where('collection_name', 'default')
                ->first();
            if ($media) {
                $service_provider->addMedia($media->getPath())
                    ->preservingOriginal()
                    ->toMediaCollection('default');
            }
            $commercial_register = Media::where('model_id', $record->id)
                ->where('collection_name', 'commercial_register')
                ->first();
            if ($commercial_register) {
                $service_provider->addMedia($commercial_register->getPath())
                    ->preservingOriginal()
                    ->toMediaCollection('commercial_register');
            }
            $record->update([
                'package_id' => $data['package_id'],
                'status' => 'approved',
            ]);
        }
        $record->update([
            'package_id' => $data['package_id'],
        ]);
        return $record;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
