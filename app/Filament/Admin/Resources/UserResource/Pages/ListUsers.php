<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
    
        // Exclude the currently logged-in user from the table
        if ($user = auth()->user()) {
            $query->where('id', '!=', $user->id);
        }
    
        // Filter the table to only show users with the 'collector' role
        $query->where('user_type','user');
    
        return $query;
    }
}
