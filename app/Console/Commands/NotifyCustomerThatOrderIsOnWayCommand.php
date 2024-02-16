<?php

namespace Tasawk\Console\Commands;

use Illuminate\Console\Command;
use Notification;
use Tasawk\Enum\OrderStatus;
use Tasawk\Lib\Utils;
use Tasawk\Models\Order;
use Tasawk\Notifications\Order\OrderStillNotAcceptedNotification;

class NotifyCustomerThatOrderIsOnWayCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-customer-that-order-is-on-way';

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

    }
}
