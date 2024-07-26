<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
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
    protected function authorizeAccess(): void
    {
        if (auth()->user()->id != $this->getRecord()->user_id) {
            abort(404);
        }
    }
}
