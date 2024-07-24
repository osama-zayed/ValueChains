<?php

namespace App\Filament\Admin\Resources\ProcedureResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Procedure;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class ProcedureChart extends ChartWidget
{
    protected static ?string $heading = 'الاجراءات';
    protected static string $color = 'info';
    protected function getData(): array
    {
            $data = Trend::model(Procedure::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'الاجراءات',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
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
