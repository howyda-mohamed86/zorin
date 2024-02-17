<?php

namespace Tasawk\Actions\Customer\Reservation;

use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use Tasawk\Models\HotelService;
use Tasawk\Models\Order;
use Tasawk\Models\User as ModelsUser;
use Tasawk\Http\Resources\Api\Hotel\Reservation\CategoryReservationResource;
use Tasawk\Enum\OrderPaymentStatus;
use Tasawk\Models\IndividualService;
use Tasawk\Services\MyFatoorah;

class CreateCategoryReservationAction
{
    use AsAction;


    public function handle(ModelsUser $user, IndividualService $service, $request, $data = [])
    {
        $cart = app('cart');
        $resource = CategoryReservationResource::make($service)->toArray(request());
        $resource['translations'] = $service->translations;
        $payment_data = [];
        $payment_status = OrderPaymentStatus::PENDING;
        $payment_type = 'MyFatoorah';
        if ($request->payment_method === "MyFatoorah") {
            if ($request->payment_method === "MyFatoorah") {
                $payment_data = MyFatoorah::init()->pay(auth()->user(), app('cart')->getTotal());
                $payment_status = OrderPaymentStatus::PENDING;
                $payment_type = 'MyFatoorah';
            } else {
                $payment_data = ["getway" => "depit"];
                $payment_status = OrderPaymentStatus::PAID;
                $payment_type = 'depit';
            }
        }
        $reservation_number = $user->reservations()->latest()->first()->reservation_number ?? null;
        $reservation_number = is_null($reservation_number) ? 001 : $reservation_number + 1;
        $mont_year = Carbon::parse($request->month)->format('Y-m');
        return $user->reservations()->create([
            "reservation_number" => $reservation_number,
            'customer_id' => $user->id,
            'service_provider_id' => $service->service_provider_id,
            'category_type' => 'category',
            'category_id' => $service->category_id,
            'individual_service_id' => $service->id,
            'month' => Carbon::parse($request->month)->format('M'),
            'date_from' => $mont_year . '-' . $request->from,
            'date_to' => $mont_year . '-' . $request->to,
            'from' => $request->from,
            'to' => $request->to,
            'total' => $cart->totals()['total'],
            'night_price' => $service->price_night,
            'night_count' => $request->to - $request->from,
            'payment_status' => $payment_status,
            'payment_data' => $payment_data,
            'payment_type' => $payment_type,
            'status' => 1,
            'notes' => 'created by ' . auth()->user()->name,
            "data" => $resource
        ]);
    }
}
