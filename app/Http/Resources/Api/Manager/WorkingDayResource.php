<?php

namespace Tasawk\Http\Resources\Api\Manager;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class WorkingDayResource extends JsonResource {


    public function toArray($request): array {
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $_days = [];
        $resource = collect($this->resource)->sortBy(function ($day) use ($days) {
            return array_search($day['day_name'], $days);
        })->values()->toArray();
        foreach ($resource as $day) {
            $_days[] = [
                'day' => $day['day_name'],
                'day_name' => __("forms.fields.weekdays.{$day['day_name']}"),
                'start' => $day['from'],
                'end' => $day['to'],
            ];

        }
        return $_days;
    }
}
