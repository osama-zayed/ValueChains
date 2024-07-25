<?php

namespace App\Filament\User\Widgets;

use App\Models\Activity;
use App\Models\Chain;
use App\Models\Domain;
use App\Models\Procedure;
use App\Models\Project;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $ProjectCount = Project::where("user_id", auth()->user()->id)->count();
        $ChainCount = Chain::where("user_id", auth()->user()->id)->count();
        $ActivityCount = Activity::where("user_id", auth()->user()->id)->count();
        $ProcedureCount = Procedure::where("user_id", auth()->user()->id)->count();

        return [
            Stat::make('عدد السلاسل', $ChainCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 5, 10, 3, 5, 5, 15])
                ->color('info'),
            Stat::make('عدد المشاريع', $ProjectCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('عدد الانشطة', $ActivityCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([170, 160, 140, 100, 140, 130, 120])
                ->color('info'),
            Stat::make('عدد الاجراءات', $ProcedureCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 5, 10, 3, 15, 4, 17])
                ->color('info'),
        ];
    }
}
