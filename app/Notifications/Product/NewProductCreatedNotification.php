<?php

namespace Tasawk\Notifications\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tasawk\Lib\Firebase;
use Tasawk\Lib\NotificationMessageParser;
use Tasawk\Models\Catalog\Product;
use Tasawk\Models\DeviceToken;
use Tasawk\Models\Order;

class NewProductCreatedNotification extends Notification {
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product) {
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

        $tokens = DeviceToken::where('user_id', $notifiable->id)->pluck('token')->unique()->toArray();
        return Firebase::make()
            ->setTokens($tokens)
            ->setTitle($this->getTitle($notifiable)[$notifiable->preferredLocale()])
            ->setBody($this->getBody($notifiable)[$notifiable->preferredLocale()])
            ->setMoreData([
                'entity_id' => $this->product->id,
                'entity_type' => 'product',
            ])
            ->do();
    }

    public function toArray($notifiable): array {
        $this->toFirebase($notifiable);


        return [
            'title' => json_encode($this->getTitle($notifiable)),
            'body' => json_encode($this->getBody($notifiable)),
            'format' => 'filament',
            'viewData' => [
                'entity_id' => $this->product->id,
                'entity_type' => 'product',
            ],
            'duration' => 'persistent'
        ];

    }

    public function getTitle($notifiable) {
        return NotificationMessageParser::init($notifiable)
            ->customerMessage('panel.notifications.new_product_created_title')
            ->parse();
    }

    public function getBody($notifiable) {

        return NotificationMessageParser::init($notifiable)
            ->customerMessage('panel.notifications.new_product_created_body', ['product_name' => $this->product->title])
            ->parse();
    }
}
