<?php

namespace Tasawk\Actions\Customer;

use Lorisleiva\Actions\Concerns\AsAction;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Tasawk\Models\Order;

class PayOrderAction {
    use AsAction;

    public function handle(Order $order) {
        $myfatoorahApiV2 = app(PaymentMyfatoorahApiV2::class);
        if (request()->get('payment_gateway') == 'cash') {
            $order->update(['payment_status' => 'paid']);
            $order->update(['payment_data' => ['gateway' => 'cash', 'method' => 'cash']]);

            return;
        }
        $payment_data = $myfatoorahApiV2->getInvoiceURL([
            'CustomerName' => $order->customer->name,
            'InvoiceValue' => $order->total->formatByDecimal(),
            'DisplayCurrencyIso' => 'SAR',
            'CustomerEmail' => $order->customer->email,
            'CallBackUrl' => route('webhooks.myfatoorah.callback'),
            'ErrorUrl' => route('webhooks.myfatoorah.callback'),
            'MobileCountryCode' => '+965',
            'CustomerMobile' => $order->customer->phone,
            'Language' => app()->getLocale(),
            'CustomerReference' => $order->id,
            'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ]);
        $order->update(['payment_data' => [...$payment_data, 'gateway' => 'myfatoorah']]);

    }

}
