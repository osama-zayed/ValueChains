<?php

namespace App\Filament\Admin\Resources\RingResource\Pages;

use App\Filament\Admin\Resources\RingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRing extends EditRecord
{
    protected static string $resource = RingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
