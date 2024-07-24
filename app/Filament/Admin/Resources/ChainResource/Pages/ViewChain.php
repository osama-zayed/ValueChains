<?php

namespace App\Filament\Admin\Resources\ChainResource\Pages;

use App\Filament\Admin\Resources\ChainResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChain extends ViewRecord
{
    protected static string $resource = ChainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
