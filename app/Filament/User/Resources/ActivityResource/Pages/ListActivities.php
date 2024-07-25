<?php

namespace App\Filament\User\Resources\ActivityResource\Pages;

use App\Filament\User\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
            if ($user = auth()->user()) {
            $query->where('user_id', '!=', $user->id);
        }
        $query->where('user_type','user');
        return $query;
    }
}
