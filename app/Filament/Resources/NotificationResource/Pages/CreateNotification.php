<?php

namespace Tasawk\Filament\Resources\NotificationResource\Pages;

use Tasawk\Filament\Resources\NotificationResource;
use Tasawk\Models\Customer;
use Tasawk\Notifications\SendAdminMessagesNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class CreateNotification extends CreateRecord {
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function fillForm(): void {

    }

    protected function handleRecordCreation(array $data): Model {
        dd($data);
        $loop = 0;
        $title = [];
        $body = [];
        $notifiable = 'all';
        $customers = [];
        foreach ($data as $lang => $list) {
            if (!$loop) {
                $notifiable = $list['notifiable'];
                $customers = $list['customers'] ?? [];
                $loop++;
            }
            $title[$lang] = $list['data']['title'];
            $body[$lang] = $list['data']['description'];
        }
        Notification::send(Customer::when($notifiable == 'specific', fn($builder) => $builder->whereIn('id', $customers))->get(), new SendAdminMessagesNotification($title, $body));
        return \Tasawk\Models\Notification::first();
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl("index");
    }
}
