<?php

namespace Tasawk\Notifications;

use Tasawk\Lib\Firebase;
use Tasawk\Models\DeviceToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendAdminMessagesNotification extends Notification {
    use Queueable;

    private $title;
    private $body;

    public function __construct($title, $body) {
        $this->title = $title;
        $this->body = $body;
    }

    public function via() {
        return ['database'];
    }

    public function toFirebase($notifiable) {
        $tokens = DeviceToken::where('user_id', $notifiable->id)->pluck('token')->unique()->toArray();
        return Firebase::make()
            ->setTokens($tokens)
            ->setTitle($this->title[$notifiable->preferredLocale()])
            ->setBody($this->body[$notifiable->preferredLocale()])
            ->do();
    }

    public function toArray($notifiable): array {
        $this->toFirebase($notifiable);
        return [
            'title' => json_encode($this->title),
            'body' => json_encode($this->body),
            'format' => 'filament',
            'viewData' => [],
            'duration' => 'persistent'
        ];

    }
}
