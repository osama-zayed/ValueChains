<?php

namespace App\Filament\Admin\Resources\RingResource\Pages;

use App\Filament\Admin\Resources\RingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRing extends ViewRecord
{
    protected static string $resource = RingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
