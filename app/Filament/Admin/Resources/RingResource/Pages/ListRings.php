<?php

namespace App\Filament\Admin\Resources\RingResource\Pages;

use App\Filament\Admin\Resources\RingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRings extends ListRecords
{
    protected static string $resource = RingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
