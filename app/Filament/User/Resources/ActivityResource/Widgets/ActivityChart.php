<?php

namespace App\Filament\User\Resources\ActivityResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Activity;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ActivityChart extends ChartWidget
{

    protected static ?string $heading = 'الانشطة';
    protected static string $color = 'info';
    protected function getData(): array
    {
            $data = Trend::model(Activity::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'الانشطة',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
