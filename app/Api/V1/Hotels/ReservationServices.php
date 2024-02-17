<?php

namespace Tasawk\Api\V1\Hotels;

use Tasawk\Api\Facade\Api;
use Tasawk\Models\Hotel;
use Tasawk\Http\Resources\Api\Hotel\HotelResource;
use Tasawk\Http\Requests\Api\Shared\HotelReservationRequest;
use Tasawk\Http\Requests\Api\Shared\HotelReservationDetailsRequest;
use Tasawk\Lib\Cart;
use Tasawk\Actions\Customer\Reservation\CreateReservationAction;
use Carbon\Carbon;
use Tasawk\Models\Reservation;

class ReservationServices
{
    public function details(HotelReservationDetailsRequest $request)
    {
        $data = [];
        /**
         * @var Cart $cart
         * */
        $cart = app('cart');
        $month = Carbon::parse($request->month)->format('Y-m');
        $date_from = $month . '-' . $request->from;
        $date_to = $month . '-' . $request->to;
        $reservation = Reservation::where('hotel_id', $request->hotel_id)
            ->where('hotel_service_id', $request->hotel_service_id)
            ->whereBetween('date_from', [$date_from, $date_to])
            ->OrwhereBetween('date_to', [$date_from, $date_to])
            ->where('category_type', 'hotel')
            ->where('status', 1)
            ->first();
        if ($reservation) {
            return Api::isError(__("This date is already reserved"));
        } else {
            $hotel = Hotel::find($request->hotel_id);
            $hotelService = $hotel->hotelServices()->find($request->hotel_service_id);
            $cart->applyService($hotelService, $request);
            $reservations = Reservation::where('hotel_id', $request->hotel_id)
                ->where('hotel_service_id', $request->hotel_service_id)
                ->where('status', 1)
                ->where('category_type', 'hotel')
                ->get();
            $res = [];
            if ($reservations->count() > 0) {
                foreach ($reservations as $reservation) {
                    $res[] = [
                        'from' => $reservation->from,
                        'to' => $reservation->to,
                    ];
                }
            }
            $data = [
                'hotel' => $hotel->name,
                'hotel_service' => $hotelService->service_name,
                'previous_reservations' => [
                    'count' => $reservations->count(),
                    'reservations' => $res,
                ],
                'month' => $request->month,
                'from' => $request->from,
                'to' => $request->to,
                'original_price' => $hotelService->price_night,
                'night_count' => $request->to - $request->from,
                'total' => $cart->formattedTotals()['total'],
            ];
        }

        return Api::isOk(__("Reservation details"), $data);
    }
    public function store(HotelReservationRequest $request)
    {
        /**
         * @var Cart $cart
         * */
        $cart = app('cart');
        $month = Carbon::parse($request->month)->format('Y-m');
        $date_from = $month . '-' . $request->from;
        $date_to = $month . '-' . $request->to;
        $reservation = Reservation::where('hotel_id', $request->hotel_id)
            ->where('hotel_service_id', $request->hotel_service_id)
            ->whereBetween('date_from', [$date_from, $date_to])
            ->OrwhereBetween('date_to', [$date_from, $date_to])
            ->where('category_type', 'hotel')
            ->where('status', 1)
            ->first();
        if ($reservation) {
            return Api::isError(__("This date is already reserved"));
        } else {
            $hotel = Hotel::find($request->hotel_id);
            $hotelService = $hotel->hotelServices()->find($request->hotel_service_id);
            $cart->applyService($hotelService, $request);
            $reservation = CreateReservationAction::run(auth()->user(), $hotelService, $request);
            $cart->clear();
            return Api::isOk(__("Reservation created successfully"));
        }
    }
}
