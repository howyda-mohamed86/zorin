<?php

namespace Tasawk\Api\V1\Customer\Profile;


use Api;
use Tasawk\Actions\Customer\PayOrderAction;
use Tasawk\Api\Core;
use Tasawk\Http\Requests\Api\Customer\Order\OrderRateRequest;
use Tasawk\Http\Resources\Api\Customer\Orders\LightOrdersResource;
use Tasawk\Http\Resources\Api\Customer\Orders\OrdersResource;
use Tasawk\Models\Order;

class OrderService {


    public function index() {
        return Api::isOk(__("Orders list"), LightOrdersResource::collection(auth()->user()->toCustomer->orders()->latest()->paginate()));
    }

    public function show(Order $order) {
        return Api::isOk(__("Order details"))->setData(OrdersResource::make($order));
    }

    public function rate(OrderRateRequest $request, Order $order) {
        $order->rate()->create($request->validated());
        return Api::isOk(__("Thanks for rating order"));

    }





//    public function complete(Order $order)
//    {
//        $order->complete();
//        $order->addTimeLine(Timeline::EVENT_COMPLETED);
//        return Api::isOk(__("Order Completed"));
//    }
//
//    public function cancel(CancelOrderRequest $request, Order $order): \Tasawk\Api\Core
//    {
//        $order->cancellationReason()->create([
//            "canceled_reason_id" => $request->get('reason_id'),
//            "note" => $request->get('note'),
//            "user_id" => auth()->id()
//        ]);
//        $order->update(['status' => OrderStatuses::CANCELED]);
//        $order->addTimeLine(Timeline::EVENT_CANCELED);
//        dispatch(fn () => Notification::send(AdminBase::administrationsUsers()->push($order->user, $order->contractor?->user), new CustomerCancelReservationNotification($order)))->afterResponse();
//        return Api::isOk("Order canceled", new OrdersResource($order));
//    }
//
//    public function rate(RateRequest $request, Order $order)
//    {
//        $order->rate()->create($request->validated());
//        $order->addTimeLine(Timeline::EVENT_RATED);
//        dispatch(fn () => Notification::send(AdminBase::administrationsUsers()->push($order->user), new CustomerRateReservationNotification($order)))->afterResponse();
//        return Api::setStatusOk()->setMessage(__("Thanks for rating order"));
//    }
//
//    public function report(ReportOrderRequest $request, Order $order)
//    {
//        if ($order->user_id != auth()->id()) {
//            return Api::isError(__("This subscription not for you!"));
//        }
//        if ($order->report) {
//            return Api::isError(__("Subscription reported before!"));
//        } else {
//            $order->report()->create([
//                'reporter_id' => auth()->id(),
//                'reporter_type' => User::class,
//                'reason_id' => $request->get('reason_id'),
//                'text' => $request->get('note')
//            ]);
//        }
//        $order->update(['status' => OrderStatuses::REPORTED]);
//        $order->addTimeLine(Timeline::EVENT_REPORTED);
//        return Api::isOk(__("Order reported"));
//    }
}
