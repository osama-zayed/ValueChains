<?php

namespace App\Filament\Widgets;

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
        $UserCount = User::where('user_type','user')->count();
        $DomainCount = Domain::count();
        $ProjectCount = Project::count();
        $ChainCount = Chain::count();
        $ActivityCount = Activity::count();
        $ProcedureCount = Procedure::count();
       
        return [
            Stat::make('عدد المستخدمين', $UserCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 2, 5, 3, 15, 4, 5])
                ->color('info'),
            Stat::make('عدد المجالات', $DomainCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 5, 10, 3, 5, 5, 15])
                ->color('info'),
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