<?php

namespace App\Filament\Admin\Resources\ChainResource\Pages;

use App\Filament\Admin\Resources\ChainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChain extends EditRecord
{
    protected static string $resource = ChainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
