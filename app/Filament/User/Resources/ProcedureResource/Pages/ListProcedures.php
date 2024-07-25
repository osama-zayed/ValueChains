<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcedures extends ListRecords
{
    protected static string $resource = ProcedureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
