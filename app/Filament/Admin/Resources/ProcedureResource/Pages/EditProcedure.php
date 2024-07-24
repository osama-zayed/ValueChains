<?php

namespace App\Filament\Admin\Resources\ProcedureResource\Pages;

use App\Filament\Admin\Resources\ProcedureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcedure extends EditRecord
{
    protected static string $resource = ProcedureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
