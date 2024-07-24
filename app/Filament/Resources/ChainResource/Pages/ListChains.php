<?php

namespace App\Filament\Resources\ChainResource\Pages;

use App\Filament\Resources\ChainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChains extends ListRecords
{
    protected static string $resource = ChainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
