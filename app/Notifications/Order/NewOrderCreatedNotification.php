<?php

namespace Tasawk\Notifications\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tasawk\Lib\Firebase;
use Tasawk\Lib\NotificationMessageParser;
use Tasawk\Models\DeviceToken;
use Tasawk\Models\Order;

class NewOrderCreatedNotification extends Notification {
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['database'];
    }


    public function toFirebase($notifiable) {
        $title = NotificationMessageParser::init($notifiable)
            ->managerMessage('panel.notifications.new_order_arrival')
            ->adminMessage('panel.notifications.new_order_arrival')
            ->parse();
        $body = NotificationMessageParser::init($notifiable)
            ->managerMessage('panel.notifications.new_order_arrival_pending_for_arrival')
            ->adminMessage('panel.notifications.new_order_arrival_pending_for_arrival')
            ->parse();
        $tokens = DeviceToken::where('user_id', $notifiable->id)->pluck('token')->unique()->toArray();
        return Firebase::make()
            ->setTokens($tokens)
            ->setTitle($title[$notifiable->preferredLocale()])
            ->setBody($body[$notifiable->preferredLocale()])
            ->setMoreData([
                'entity_id' => $this->order->id,
                'entity_type' => 'order',
            ])
            ->do();
    }

    public function toArray($notifiable): array {
        $this->toFirebase($notifiable);

        $title = NotificationMessageParser::init($notifiable)
            ->managerMessage('panel.notifications.new_order_arrival')
            ->adminMessage('panel.notifications.new_order_arrival')
            ->parse();
        $body = NotificationMessageParser::init($notifiable)
            ->managerMessage('panel.notifications.new_order_arrival_pending_for_arrival')
            ->adminMessage('panel.notifications.new_order_arrival_pending_for_arrival')
            ->parse();
        return [
            'title' => json_encode($title),
            'body' => json_encode($body),
            'format' => 'filament',
            'viewData' => [
                'entity_id' => $this->order->id,
                'entity_type' => 'order',
            ],
            'duration' => 'persistent'
        ];

    }
}
