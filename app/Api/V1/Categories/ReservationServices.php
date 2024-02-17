<?php

namespace Tasawk\Api\V1\Categories;

use Tasawk\Api\Facade\Api;
use Tasawk\Models\Hotel;
use Tasawk\Http\Resources\Api\Hotel\HotelResource;
use Tasawk\Http\Requests\Api\Shared\HotelReservationRequest;
use Tasawk\Http\Requests\Api\Shared\CategoryReservationDetailsRequest;
use Tasawk\Lib\Cart;
use Tasawk\Actions\Customer\Reservation\CreateCategoryReservationAction;
use Tasawk\Http\Requests\Api\Shared\CategoryReservationRequest;
use Carbon\Carbon;
use Tasawk\Models\Reservation;
use Tasawk\Models\Catalog\Category;

class ReservationServices
{
    public function details(CategoryReservationDetailsRequest $request)
    {
        $data = [];
        /**
         * @var Cart $cart
         * */
        $cart = app('cart');
        $month = Carbon::parse($request->month)->format('Y-m');
        $date_from = $month . '-' . $request->from;
        $date_to = $month . '-' . $request->to;
        $reservation = Reservation::where('category_id', $request->category_id)
            ->where('individual_service_id', $request->individual_service_id)
            ->whereBetween('date_from', [$date_from, $date_to])
            ->OrwhereBetween('date_to', [$date_from, $date_to])
            ->where('category_type', 'category')
            ->where('status', 1)
            ->first();
        if ($reservation) {
            return Api::isError(__("This date is already reserved"));
        } else {
            $category = Category::find($request->category_id);
            $individualService = $category->individualServices()->find($request->individual_service_id);
            $cart->applyIndividualService($individualService, $request);
            $reservations = Reservation::where('category_id', $request->category_id)
                ->where('individual_service_id', $request->individual_service_id)
                ->where('category_type', 'category')
                ->where('status', 1)
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
                'category' => $category->name,
                'individual_service' => $individualService->service_name,
                'previous_reservations' => [
                    'count' => $reservations->count(),
                    'reservations' => $res,
                ],
                'month' => $request->month,
                'from' => $request->from,
                'to' => $request->to,
                'original_price' => $individualService->price_night,
                'night_count' => $request->to - $request->from,
                'total' => $cart->formattedTotals()['total'],
            ];
        }
        return Api::isOk(__("Reservation details"), $data);
    }
    public function store(CategoryReservationRequest $request)
    {
        /**
         * @var Cart $cart
         * */
        $cart = app('cart');
        $month = Carbon::parse($request->month)->format('Y-m');
        $date_from = $month . '-' . $request->from;
        $date_to = $month . '-' . $request->to;
        $reservation = Reservation::where('category_id', $request->category_id)
            ->where('individual_service_id', $request->individual_service_id)
            ->whereBetween('date_from', [$date_from, $date_to])
            ->OrwhereBetween('date_to', [$date_from, $date_to])
            ->where('category_type', 'category')
            ->where('status', 1)
            ->first();
        if ($reservation) {
            return Api::isError(__("This date is already reserved"));
        } else {
            $category = Category::find($request->category_id);
            $individualService = $category->individualServices()->find($request->individual_service_id);
            $cart->applyIndividualService($individualService, $request);
            $reservation = CreateCategoryReservationAction::run(auth()->user(), $individualService, $request);
            $cart->clear();
            return Api::isOk(__("Reservation created successfully"));
        }
    }
}
