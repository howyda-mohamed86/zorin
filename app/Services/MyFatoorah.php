<?php

namespace Tasawk\Services;

use Taswak\Http\Controllers\Controller;
use Taswak\Models\User;
use Illuminate\Support\Facades\Notification;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Tasawk\Models\Subscription;
use Tasawk\Notifications\CustomerSubscribeInPlanNotification;

class MyFatoorah extends \Tasawk\Http\Controllers\Controller {

    public $mfObj;

    /**
     * create MyFatoorah object
     */
    public function __construct() {
        $this->mfObj = new PaymentMyfatoorahApiV2(config('myfatoorah.api_key'), config('myfatoorah.country_iso'), config('myfatoorah.test_mode'));
    }

    static public function init() {
        return new static();
    }

    /**
     * Create MyFatoorah invoice
     *
     * @return array|\Illuminate\Http\Response
     */
    public function pay(\Tasawk\Models\User $user, $cost) {
        $paymentMethodId = 0; // 0 for MyFatoorah invoice or 1 for Knet in test mode
        return $this->mfObj->getInvoiceURL($this->getPayLoadData($user, $cost), $paymentMethodId);
    }

    /**
     *
     * @param int|string $orderId
     * @return array
     */
    private function getPayLoadData($user, $cost) {
        $callbackURL = route('webhooks.myfatoorah.callback');
        return [
            'CustomerName' => $user->name,
            'InvoiceValue' => $cost,
            'DisplayCurrencyIso' => 'SAR',
            'CustomerEmail' => $user->email,
            'CallBackUrl' => $callbackURL,
            'ErrorUrl' => $callbackURL,
            'MobileCountryCode' => '+965',
            'CustomerMobile' => $user->phone,
            'Language' => 'ar',
            'CustomerReference' => $user->id,
            'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }

    /**
     * Get MyFatoorah payment information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback() {
        $data = $this->mfObj->getPaymentStatus(request('paymentId'), 'PaymentId');
        if ($data->InvoiceStatus == 'Paid') {
            $subscription = Subscription::withoutGlobalScopes()->where('payment_data->invoiceId', $data->InvoiceId)->first();
            if ($subscription && $subscription->payment_status != 'paid') {
                $subscription->update(['payment_status' => 'paid', 'payment_data' => array_merge($subscription->payment_data, ['paid_at' => now()])]);
                Notification::send($subscription->user,new CustomerSubscribeInPlanNotification($subscription));
            }
            return response()->json(['IsSuccess' => 'true', 'Message' => 'invoice paid']);

        }
        return response()->json(['IsSuccess' => 'true', 'Message' => $data->InvoiceError]);

    }

}
