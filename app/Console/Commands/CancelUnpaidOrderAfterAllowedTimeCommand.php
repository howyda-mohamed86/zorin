<?php

namespace Tasawk\Console\Commands;

use Illuminate\Console\Command;
use Tasawk\Enum\OrderStatus;
use Tasawk\Models\Order;

class CancelUnpaidOrderAfterAllowedTimeCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-unpaid-order-after-allowed-time';

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
        $orders = Order::where('status', OrderStatus::PENDING_FOR_CUSTOMER_PAY)->where('date', '<', now()->subMinutes(60))->get();

        foreach ($orders as $order) {
            //0 refer to customer not responded
            //-1 refer to manager not responded
            $order->cancel(0);
        }
    }
}
