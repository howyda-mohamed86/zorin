<?php

namespace Tasawk\Console\Commands;

use Illuminate\Console\Command;
use Notification;
use Tasawk\Enum\OrderStatus;
use Tasawk\Lib\Utils;
use Tasawk\Models\Order;
use Tasawk\Notifications\Order\OrderStillNotAcceptedNotification;

class ResendNotificationToManagersWithPendingOrdersCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resend-notification-to-managers-with-pending-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command For cancel orders after allowed time';

    /**
     * Execute the console command.
     */
    public function handle() {
        $orders = Order::where('status', OrderStatus::PENDING_FOR_ACCEPT_ORDER)->where('date', '<', now()->subMinutes(10))->get();
        foreach ($orders as $order) {
            Notification::send(Utils::getAdministrationUsers($order->branch_id), new OrderStillNotAcceptedNotification($order));
        }
    }
}
