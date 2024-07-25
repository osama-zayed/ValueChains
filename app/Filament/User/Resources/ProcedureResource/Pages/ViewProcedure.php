<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProcedure extends ViewRecord
{
    protected static string $resource = ProcedureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}