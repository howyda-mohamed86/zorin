<?php

use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Tasawk\Enum\ExecutingOrderStatus;
use Tasawk\Enum\OrderStatus;
use Tasawk\Lib\Utils;
use Tasawk\Models\Branch;
use Tasawk\Models\Catalog\Category;
use Illuminate\Support\Facades\Route;
use Tasawk\Models\Order;
use Tasawk\Models\User;
use Tasawk\Notifications\Branch\BranchMaintenanceModeChangedNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'admin');


Route::get('orders/{order}/invoice', function (Order $order) {
    return view('filament.pages.print', ['order' => $order]);
})->name('orders.invoice');

Route::get('webhooks/myfatoorah/callback', function (PaymentMyfatoorahApiV2 $myfatoorahApiV2) {
    $response = $myfatoorahApiV2->getPaymentStatus(request()->get('Id'), 'PaymentId');
    $order = Order::where('payment_data->invoiceId', $response->InvoiceId)->first();
    if ($response->InvoiceStatus == 'Paid') {
        if ($order->payment_status != 'paid') {
            $order->update([
                'status' => ExecutingOrderStatus::ACCEPTED,
                'payment_data' => array_merge($order->payment_data, [...collect($response)->toArray(), 'method' => $response->focusTransaction->PaymentGateway,'paid_at'=>now()]),
                'payment_status' => 'paid',

            ]);
        }

        return Api::isOk('Payment is done successfully');
    }
    return Api::isError('something went wrong');
})->name('webhooks.myfatoorah.callback');
