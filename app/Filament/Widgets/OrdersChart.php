<?php

namespace Tasawk\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Tasawk\Models\Order;

class OrdersChart extends ChartWidget {
    use HasWidgetShield;
    protected static ?int $sort = 2;
    public ?string $filter = "month";

    protected function getData(): array {

        $activeFilter = $this->filter;
        $filter = match ($activeFilter) {
            'today' => ['start' => now()->startOfDay(), 'end' => now()->endOfDay(), 'per' => "perHour"],
            'week' => ['start' => now()->startOfWeek(), 'end' => now()->endOfWeek(), 'per' => "perDay"],
            'month' => ['start' => now()->firstOfMonth(), 'end' => now()->lastOfMonth(), 'per' => "perDay"],
            'year' => ['start' => now()->startOfYear(), 'end' => now()->endOfYear(), 'per' => "perMonth"],
        };

        $data = Trend::query(
            Order::query()
        )
            ->between(
                start: $filter['start'],
                end: $filter['end'],
            )
            ->dateColumn('date')
            ->dateAlias('datetime')
            ->{$filter['per']}()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => __('sections.orders'),
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string {
        return 'line';
    }

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string|null {
        return __('panel.stats.orders');
    }

    public function getColumnSpan(): int|string|array {
        return 2;
    }

    /**
     * @return string|null
     */
    public function getMaxHeight(): ?string {
        return "400px";
    }

    protected function getFilters(): ?array {
        return [
            'today' => __('panel.stats.today'),
            'week' => __('panel.stats.week'),
            'month' => __('panel.stats.last_month'),
            'year' => __('panel.stats.year'),
        ];
    }
    public function getDescription(): ?string
    {
        return __('panel.stats.orders_chart_description');
    }
    public function getTableHeading(): ?string {
        return $this->getHeading();
    }
    protected function getOptions(): array {
        return [
            'scales' => [
                'y' => [
                    "ticks" => [
                        "stepSize" => 1
                    ]

                ],
            ]

        ];
    }
}
